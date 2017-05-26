<?php 

$redis = new Redis();
$redis->connect('rm20338.eos.grid.sina.com.cn', 20338);
// $redis->connect('localhost');

$data_str = $redis->get('source_update_list');
$data_str = json_decode($data_str, true);
if (empty($data_str)) {
	exit;
}
$curl = new curl();
foreach ($data_str as $val) {
	if (empty($val['id']) || !isset($val['score'])) {
		continue;
	}
	$url = sprintf('http://i.search.toutiao.weibo.cn:9200/source_score/source/%d', $val['id']);
    $results = $curl->get($url);
    $results = json_decode($results, true);

	// 发生未知错误
    if (!is_array($results) || !isset($results['found'])) {
        continue;
    }
    // 文章未找到
    if (false === $results['found']) {
    	continue;
    }
    $_source = $results['_source'];
    $status = $_source['status'];


    $url = sprintf('http://i.search.toutiao.weibo.cn:9200/source_score/source/%d/_update', $val['id']);
    $data = array(
        'doc' => array(
            'score' => $val['score'],
            'status' => $status,
            'updated_at' => time()
        )
    );

    $results = curlPost($url, $data);

    // 发生未知错误
    if (false === $results) {
    	continue;
    }

	// 错误
    if (isset($results['error'])) {
    	continue;
    }

    // 日志
    $currMicroTime = time() * 1000 + mt_rand(1, 999);
    $url = sprintf('http://i.search.toutiao.weibo.cn:9200/source_score_log/log/%d', $currMicroTime);
    $data = array(
        "action" => 'manual operation',
        "detail" => json_encode(
            array(
                'from' => $_source,
                'to' => $val['score']
            )
        ),
        "oid" => "",
        "editor" => 'script',
        "source" => $_source['source'],
        "source_id" => $_source['source_id'],
        "time" => $currMicroTime,
        "uid" => $_source['uid']
    );
    $results = curlPost($url, $data);
}
// 清空数据
// $redis->set('source_update_list', '');

function curlPost($url, $data)
{
	$json = json_encode($data);
    $opt = array(
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json; charset=utf-8'
        )
    );
    $client = new curl();
    $results = $client->cPost($url, $json, $opt);
    $results = json_decode($results, true);
    if (!is_array($results)) {
        return false;
    }
    return $results;
}


class curl
{
    private $ch = null;
    private $opt = array(
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,	//输出反回字符串
    );

    public function get($url)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        foreach ($this->opt as $key => $value) {
            curl_setopt($this->ch, $key, $value);
        }
        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }

    public function post($url, $data)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_URL, $url);

        if (is_array($data) || is_object($data)) {
            $curlPost = http_build_query($data);
        } else {
            $curlPost = $data;
        }

        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $curlPost);
        foreach ($this->opt as $key => $value) {
            curl_setopt($this->ch, $key, $value);
        }

        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }

    public function cPost($url, $data, $opt = array())
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        if (is_array($data) || is_object($data)) {
            $curlPost = http_build_query($data);
        } else {
            $curlPost = $data;
        }

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $curlPost);
        foreach ($opt as $key => $value) {
        	$this->opt[$key] = $value;
        }

        foreach ($this->opt as $key => $value) {
            curl_setopt($this->ch, $key, $value);
        }

        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;

    }
}