<?php

namespace Drupal\Tests\group_behavior\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Simple browser test.
 *
 * @group group_behavior
 */
class AdminPageTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'group_behavior',
  ];

  /**
   * Theme to enable.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests that the /admin page returns a 200.
   */
  public function testAdminPage() {
    $this->drupalLogin($this->rootUser);
    $this->drupalGet('admin');
    $this->assertResponse(200);

    // Ensure that the test is not marked as risky because of no assertions.
    // see https://gitlab.com/weitzman/drupal-test-traits/-/commit/82bf5059908f9073b3468cb7313960da72176d9a
    $this->addToAssertionCount(1);
  }

}
