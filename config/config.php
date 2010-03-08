<?php

// for opDiaryPlugin
$this->dispatcher->connect(
  'op_action.post_execute_diary_create',
  array('opImKayacComListener', 'listenToPostDiaryCreate')
);
$this->dispatcher->connect(
  'op_action.post_execute_diaryComment_create',
  array('opImKayacComListener', 'listenToPostDiaryCommentCreate')
);

// for opCommunityTopicPlugin
$this->dispatcher->connect(
  'op_action.post_execute_communityTopic_create',
  array('opImKayacComListener', 'listenToPostCommunityTopicCreate')
);
$this->dispatcher->connect(
  'op_action.post_execute_communityEvent_create',
  array('opImKayacComListener', 'listenToPostCommunityEventCreate')
);

// for opMessagePlugin
$this->dispatcher->connect(
  'op_action.post_execute_message_sendToFriend',
  array('opImKayacComListener', 'listenToPostMessageSendToFriend')
);

// for opAlbumPlugin
$this->dispatcher->connect(
  'op_action.post_execute_album_create',
  array('opImKayacComListener', 'listenToPostAlbumCreate')
);
