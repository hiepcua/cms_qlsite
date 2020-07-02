<?php
// ===================PITNEX.COM================
function getBalance($URL_API, $wallet) {
    $url = $URL_API.'/api/pit/getBalance/'.$wallet;
    $req= Curl_Get($url);
	if($req['status']==true && isset($req['balance'])) return str_replace(' PIT','',$req['balance']);
	else return 0;
}
function transferCoin($URL_API, $jsonBody) {
    $url = $URL_API.'/api/pit/transfer';
    return Curl_Post($url,$jsonBody);
}
function checkResourceStatus($URL_API, $jsonBody) {
    $url = $URL_API.'/api/account/checkResourceStatus';
    return Curl_Post($url,$jsonBody);
}
function getListTransaction($URL_API, $jsonBody) {
    $jsonBody = '{"account":"{vnpitaccount}", "last":"7d"}';
    $url = $URL_API.'/api/account/getListTransaction';
    return Curl_Post($url,$jsonBody);
}
function adminStatus($URL_API) {
    $url = $URL_API.'/api/account/adminStatus';
    return Curl_Get($url);
}
function getListStaked($URL_API) {
    $url = $URL_API.'/api/account/getListStaked';
    return Curl_Get($url);
}
function genKeys($URL_API) {
    $url = $URL_API.'/api/account/genkey';
    return Curl_Get($url);
}
function createNewAccount($URL_API, $jsonBody) {
    $url = $URL_API.'/api/account/new';
    return Curl_Post($url,$jsonBody);
}
?>