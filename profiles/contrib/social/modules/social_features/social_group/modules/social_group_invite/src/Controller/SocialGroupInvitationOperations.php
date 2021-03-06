<?php

namespace Drupal\social_group_invite\Controller;

use Drupal\group\Entity\GroupContent;
use Drupal\group\Entity\GroupContentInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\ginvite\Controller\InvitationOperations;

/**
 * Handles Accept operation for invited users.
 */
class SocialGroupInvitationOperations extends InvitationOperations {

  /**
   * Create user membership and change invitation status.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   HTTP request.
   * @param \Drupal\group\Entity\GroupContentInterface $group_content
   *   Invitation entity.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response object.
   */
  public function accepted(Request $request, GroupContentInterface $group_content) {
    $group = $group_content->getGroup();
    $contentTypeConfigId = $group_content->getGroup()
      ->getGroupType()
      ->getContentPlugin('group_membership')
      ->getContentTypeConfigId();

    /** @var \Drupal\group\Entity\GroupContentInterface $group_content */
    $group_content = GroupContent::create([
      'type' => $contentTypeConfigId,
      'entity_id' => $group_content->get('entity_id')->getString(),
      'content_plugin' => 'group_membership',
      'gid' => $group->id(),
      'uid' => $group_content->getOwnerId(),
      'group_roles' => $group_content->get('group_roles')->getValue(),
    ]);

    $group_content->save();

    return new RedirectResponse($group->toUrl()->toString());
  }

}
