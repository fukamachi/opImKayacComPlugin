<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opImKayacComListener
 *
 * @package    OpenPNE
 * @subpackage im_kayac_com
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.com>
 */

class opImKayacComListener
{
  static public function listenToPostDiaryCreate()
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();
    $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($memberId);
    $diary = Doctrine::getTable('Diary')->createQuery()
      ->where('member_id = ?', $memberId)
      ->orderBy('created_at DESC')
      ->fetchOne();
    opImKayacComPluginToolkit::notifyAll(
      $friendIds,
      'New diary arrived',
      sfContext::getInstance()->getController()->genUrl('@diary_show?id='.$diary->getId(), true)
    );
  }

  static public function listenToPostDiaryCommentCreate()
  {
    $diaryId = sfContext::getInstance()->getRequest()->getParameter('id');
    $diary = Doctrine::getTable('Diary')->find($diaryId);
    if (!$diary || $diary->getMemberId() === sfContext::getInstance()->getUser()->getMemberId())
    {
      return;
    }

    opImKayacComPluginToolkit::notify(
      $diary->getMemberId(),
      'You\'ve got a new comment',
      sfContext::getInstance()->getController()->genUrl('@diary_show?id='.$diaryId.'&comment_count='.$diary->countDiaryComments(true), true)
    );
  }

  static public function listenToPostCommunityTopicCreate()
  {
  }

  static public function listenToPostCommunityEventCreate()
  {
  }

  static public function listenToPostMessageSendToFriend()
  {
  }

  static public function listenToPostAlbumCreate()
  {
  }
}
