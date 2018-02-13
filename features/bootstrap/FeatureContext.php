<?php

/**
 * @file
 * Adds custom steps implementation.
 */

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

define('DASH_CONTACT_SELECT_NOT_SHOW_INTEREST', 1);
define('DASH_CONTACT_SELECT_SHOW_INTEREST', 2);


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * @Given I log in as :arg1
   */
  public function iLogInAs($username) {
    $base_url = $this->getMinkParameter('base_url');
    $login_link = $this->getDriver('drush')->drush('uli', array(
      "'${username}'",
      '--browser=0',
      "--uri=${base_url}",
    ));
    $login_link = trim($login_link);
    $this->getSession()->visit($login_link);
    if (!$this->loggedIn()) {
      throw new Exception('Unable to login as user ' . $username);
    }
    $this->user = user_load_by_name($username);
  }

  /**
   * @AfterStep
   */
  public function takeScreenShotAfterFailedStep(AfterStepScope $scope) {
    if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {
      $driver = $this->getSession()->getDriver();

      $path = $this->getScreenshotDebugDirectory();
      $steptext = $scope->getStep()->getText();
      $filename = preg_replace('#[^a-zA-Z0-9\._-]#', '', $steptext);

      if ($driver instanceof GoutteDriver) {
        $content = $this->getSession()->getDriver()->getContent();
        file_put_contents($path . '/' . $filename . '.html', $content);
      }
      else if ($driver instanceof Selenium2Driver) {
        file_put_contents($path . '/' . $filename . '.png', $driver->getScreenshot());
      }

    }
  }

  /**
   * Get Screenshot Debug directory.
   *
   * @return string
   *   The Screenshot Debug directory path.
   */
  protected function getScreenshotDebugDirectory() {
    $screenshot_dir = getenv('BEHAT_SCREENSHOT_DEBUG');
    return $screenshot_dir ? $screenshot_dir : __DIR__ . '/../screenshots';
  }

  /**
   * @BeforeScenario @email
   */
  public function beforeEmailScenario() {
    $this->mailConfig = \Drupal::configFactory()->getEditable('system.mail');
    $this->savedMailDefaults = $this->mailConfig->get('interface.default');
    $this->mailConfig->set('interface.default', 'test_mail_collector')->save();
    \Drupal::state()->set('system.test_mail_collector', array());
  }

  /**
   * @AfterScenario @email
   */
  public function afterEmailScenario() {
    $this->mailConfig->set('interface.default', $this->savedMailDefaults)->save();
  }

  /**
   * Helper to get the last email.
   *
   * @todo this method does a cache reset each time, optimize this.
   */
  public function getLastEmail() {
    // Reset state cache.
    \Drupal::state()->resetCache();
    $mails = \Drupal::state()->get('system.test_mail_collector') ?: array();
    $last_mail = end($mails);
    if (!$last_mail) {
      throw new Exception('No mail was sent.');
    }
    return $last_mail;
  }

  /**
   * @Then the last email sent should have recipient :recipient
   */
  public function theLastMailSentShouldHaveRecipient($recipient) {
    $last_mail = $this->getLastEmail();
    if ($last_mail['to'] != $recipient) {
      throw new Exception("Recpient mismatch: " . $last_mail['to'] . " | " . $recipient);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then the last email sent should have subject :subject
   */
  public function theLastMailSentShouldHaveSubject($subject) {
    $last_mail = $this->getLastEmail();
    if ($last_mail['subject'] != $subject) {
      throw new Exception("Subject mismatch:" . $last_mail['subject'] . " | " . $subject);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then the last email sent should have a body containing :text
   */
  public function theLastMailSentShouldHaveBodyContainint($text) {
    $last_mail = $this->getLastEmail();
    if (strpos($last_mail['body'], $text) === FALSE) {
      throw new Exception("Body text not found:" . $last_mail['body'] . " | " . $text);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then no email should have been sent
   */
  public function noEmailShouldBeSent() {
    // Reset state cache.
    \Drupal::state()->resetCache();
    $mails = \Drupal::state()->get('system.test_mail_collector') ?: array();
    if (!empty($mails)) {
      throw new Exception('Emails were sent/');
    }
  }

  /**
   * @Then check if :element value is not empty
   */
  public function checkIfValueIsNotEmpty($element) {
    $session = $this->getSession();
    $page = $session->getPage();
    $element_css = $page->find('css', $element);
    if ($element_css) {
      $element_value = $element_css->getHtml();
      if (empty($element_value)) {
        throw new Exception($element . ' is empty!/');
      }
      else {
        echo $element . " element is not empty, ok.";
      }
    }
    else {
      throw new Exception($element . ' not exists!/');
    }
  }

  /**
   * @Then check if default theme is :theme
   */
  public function checkIfDefaultThemeIs($theme) {
    $config = \Drupal::config('system.theme');
    $config_specific_default = $config->get('default');
    if ($config_specific_default != $theme) {
      throw new Exception("Default theme is " . $config_specific_default . ", not " . $theme);
    }
  }

  /**
   * @Then check if user :user exists and has role :role
   */
  public function checkIfUserExistsAndHasRole($user, $role) {
    // Load drupal Users.
    $user_storage = \Drupal::service('entity_type.manager')->getStorage('user');
    $ids = $user_storage->getQuery()
      ->execute();
    $users = $user_storage->loadMultiple($ids);
    // Set a variable if user is not found.
    $user_find = FALSE;

    foreach ($users as $user_el) {
      $username = $user_el->getUsername();
      if ($username == $user) {
        echo $user . ' found! as ' . $username . '.';
        $user_find = TRUE;
        // Check role of this user.
        if (!$user_el->hasRole($role)) {
          throw new Exception($user . ' has not role ' . $role);
        }
        else {
          echo 'And ' . $username . ' has role ' . $role;
        }
        break;
      }
    }
    // Return an exception if user not found.
    if (!$user_find) {
      throw new Exception('User not found!');
    }
  }

  /**
   * @Then check if bundle :bundle of entity type :entity_type exists
   */
  public function checkIfEntityBundleHasField($entity_type, $bundle) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = \Drupal::service('entity_type.manager');

    if ( $entityTypeManager->hasDefinition($entity_type) === FALSE ) {
      $message = 'The entity type %s does not exist';
      throw new \Exception(sprintf($message, $entity_type));
    }

    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo */
    $bundleInfo = \Drupal::service("entity_type.bundle.info");

    $entityBundleInfo = $bundleInfo->getBundleInfo($entity_type);

    if (!key_exists($bundle, $entityBundleInfo)) {
      $message = 'A bundle called %s does not exists in entity type %s';
      throw new \Exception(sprintf($message, $bundle, $entity_type));
    }
  }

  /**
   * @Then check if bundle :bundle of entity type :entity_type has field :field
   */
  public function checkIfEntityTypeHasField($entity_type, $bundle, $field) {

    $this->checkIfEntityBundleHasField($entity_type, $bundle);

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $fieldManager */
    $fieldManager = \Drupal::service('entity_field.manager');
    $fields = [];

    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fieldDefinitions */
    $fieldDefinitions = $fieldManager->getFieldDefinitions($entity_type, $bundle);

    /** @var \Drupal\Core\Field\BaseFieldDefinition $fieldDefinition */
    foreach ($fieldDefinitions as $fieldDefinition) {
      $fields[] = $fieldDefinition->getName();
    }

    if (!in_array($field, $fields)) {
      $message = 'A field called %s does not exists in entity type 
      %s and bundle %s';
      throw new \Exception(sprintf($message, $field, $entity_type, $bundle));
    }
  }

  /**
   * @Then check if taxonomy term with name :term_name exists in taxonomy vocabulary with vid :vid
   */
  public function checkIfTaxonomyVocabularyHasTerm($term_name, $vid) {

    $this->checkIfEntityBundleHasField('taxonomy_term', $vid);

    /** @var \Drupal\Core\Entity\EntityStorageInterface $entityStorage */
    $entityStorage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');

    $ret = $entityStorage->loadByProperties(
      [
        'vid' => $vid,
        'name' => $term_name
      ]
    );

    if (empty($ret)) {
      $message = 'A taxonomy term %s does not exists in vocabulary %s';
      throw new \Exception(sprintf($message, $term_name, $vid));
    }
  }

  /**
   * @When /^I do not follow redirects$/
   */
  public function iDoNotFollowRedirects() {
    $this->getSession()->getDriver()->getClient()->followRedirects(false);
  }

  /**
   * @When /^I follow redirects$/
   */
  public function iFollowRedirects() {
    $this->getSession()->getDriver()->getClient()->followRedirects(true);
  }

  /**
   * @Then /^I (?:am|should be) redirected to "([^"]*)"$/
   */
  public function iAmRedirectedTo($actualPath) {
      $headers = $this->getSession()->getResponseHeaders();
      if (!isset($headers['Location'][0])) {
      $message = 'Location not found in headers';
      throw new \Exception($message);
    }

    $redirectComponents = parse_url($headers['Location'][0]);
    if ($redirectComponents['path'] !== $actualPath) {
      $message = 'The actual path %s is different from the expected one %s';
      throw new \Exception(sprintf($message, $redirectComponents['path'], $actualPath));
    }
  }

  /**
   * @AfterScenario
   */
  public function after(AfterScenarioScope $scope) {
    if ($scope->getScenario()->hasTag("ClearRedirectsSetting")) {
      $this->getSession()->getDriver()->getClient()->followRedirects(true);
    }
  }

}
