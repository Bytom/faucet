<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>比原链国密测试网水龙头</title>
<body style="background-image:url(faucet_bg.png);background-repeat:no-repeat">

<?php
/* PPK Testnet Faucet DEMO baesd Bytom Blockchain */
/*         PPkPub.org  20180917                   */  
/*    Released under the MIT License.             */

require_once "inc_gm.php";

define('FAUCET_AMOUNT_mBTM',10*1000); //注意单位:mBTM

$your_address = addslashes(@$_REQUEST['your_address']); //避免异常输入字符的安全性问题
$address_flag=substr($your_address,0,1);
if( !(strcasecmp($address_flag,'g')==0 || strcasecmp($address_flag,'s')==0) ){
?>
<h3>比原链国密测试网水龙头（BytomGmTestnetFaucet）</h3>
<font size="-2">
<p>请输入比原链国密测试网钱包地址（以gm起始）来领取测试币。<br>
Please input Bytom testnet address which is start from gm...</p>
<form name="form_faucet" id="form_faucet" action="faucet_gm.php" method="get">
<p>
你的比原测试钱包地址：<input type="text" name="your_address" id="your_address" value="" size=50 ><br><br>
　　　　　　　　　　　<input type='submit' id="game_send_trans_btn" value=' 马上免费领取 Get now for free! '> 
</p>
</form>

<P>比原官方钱包的下载和安装说明参考这里：<a href="http://8btc.com/thread-181537-1-1.html" target="_blank">http://8btc.com/thread-181537-1-1.html</a> （注意运行钱包时选择测试网络才能参与领取测试币）<p>

</font>
<?php
  exit(0);
}

$asset_id=addslashes(@$_REQUEST['asset_id']); //避免异常输入字符的安全性问题

$current_account_info=getNextAccountInfo();

$tmp_url=BTM_NODE_API_URL.'build-transaction';

if(strlen($asset_id)==0){
  $tmp_post_data='{
    "base_transaction": null,
    "actions": [
      {
        "account_id": "'.$current_account_info['id'].'",
        "amount": '.( FAUCET_AMOUNT_mBTM+TX_GAS_AMOUNT_mBTM ).'00000,
        "asset_id": "'.BTM_ASSET_ID.'",
        "type": "spend_account"
      },
      {
        "amount": '.FAUCET_AMOUNT_mBTM.'00000,
        "asset_id": "'.BTM_ASSET_ID.'",
        "address": "'.$your_address.'",
        "type": "control_address"
      }
    ],
    "ttl": 0,
    "time_range": '.time().'
  }';
}else{
  $faucet_token_amount=1000;
  $tmp_post_data='{
    "base_transaction": null,
    "actions": [
      {
        "account_id": "'.$current_account_info['id'].'",
        "amount": '.TX_GAS_AMOUNT_mBTM .'00000,
        "asset_id": "'.BTM_ASSET_ID.'",
        "type": "spend_account"
      },
      {
        "account_id": "'.$current_account_info['id'].'",
        "amount": '.$faucet_token_amount .',
        "asset_id": "'.$asset_id.'",
        "type": "spend_account"
      },
      {
        "amount": '.$faucet_token_amount.',
        "asset_id": "'.$asset_id.'",
        "address": "'.$your_address.'",
        "type": "control_address"
      }
    ],
    "ttl": 0,
    "time_range": '.time().'
  }';
}
$obj_resp=sendBtmTransaction($tmp_post_data,$current_account_info);

if(strcmp($obj_resp['status'],'success')!==0){
    echo "发送比原交易失败，请稍候重试！Failed to send transaction to Bytom blockchain!\n",json_encode($obj_resp);
    echo "Debug Account:", $current_account_info['id'];
    exit(-1);
}

echo "发送比原交易成功，交易ID: ", $obj_resp['data']['tx_id'], "<br><br>\n";
echo '请等待2-3分钟得到比原链出块确认，然后打开你的比原钱包即可看到（注意钱包需接入比原测试网络testnet）。';
echo '<p><a href="http://test.blockmeta.com/faucet_gm.php">返回</a></p>';