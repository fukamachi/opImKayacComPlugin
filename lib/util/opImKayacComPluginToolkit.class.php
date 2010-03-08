<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opImKayacComPluginToolkit
 *
 * @package    OpenPNE
 * @subpackage im_kayac_com
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.com>
 */
class opImKayacComPluginToolkit
{
  protected static
    $url = 'http://im.kayac.com/api/post/%s';

  static public function postToImKayacCom($username, $params = array())
  {
    $params['message'] = '[op3] '.$params['message'];
    $params = http_build_query($params, '', '&');
    $header = array(
      'Content-Type: application/x-www-form-urlencoded',
      'Content-Length: '.strlen($params),
    );
    $options = array(
      'http' => array(
        'method' => 'POST',
        'header' => implode("\r\n", $header),
        'content' => $params,
      )
    );

    return self::checkResponse(file_get_contents(sprintf(self::$url, $username), false, stream_context_create($options)));
  }

  static public function notify($memberId, $message, $handler = '')
  {
    $imKayacCom = self::getImKayacCom($memberId);

    if ($imKayacCom['username'])
    {
      $params = array('message' => $message, 'handler' => $handler);
      if ($imKayacCom['secret_key'])
      {
        $params['sig'] = sha1($message.$imKayacCom['secret_key']);

      }
      else if ($imKayacCom['password'])
      {
        $params['password'] = $imKayacCom['password'];
      }

      return self::postToImKayacCom($imKayacCom['username'], $params);
    }
  }

  static public function notifyAll($memberIds = array(), $message, $handler = '')
  {
    $responses = array();
    foreach ($memberIds as $memberId)
    {
      $responses[] = self::notify($memberId, $message, $handler);
    }

    return $responses;
  }

  static protected function getImKayacCom($memberId)
  {
    $memberConfig = Doctrine::getTable('MemberConfig');
    return array(
      'username' => $memberConfig->retrieveByNameAndMemberId('im_kayac_com_username', $memberId)->getValue(),
      'password' => $memberConfig->retrieveByNameAndMemberId('im_kayac_com_password', $memberId)->getValue(),
      'secret_key' => $memberConfig->retrieveByNameAndMemberId('im_kayac_com_secret_key', $memberId)->getValue(),
    );
  }

  static protected function checkResponse($response = '')
  {
    $responseJson = json_decode($response, true);
    if ($responseJson['error'])
    {
      throw new Exception($responseJson['error']);
    }

    return $responseJson;
  }
}
