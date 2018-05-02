<?php
  namespace Drupal\agid_twitter_block\Plugin\Block;

  require '../vendor/autoload.php';

  use Drupal\Core\Block\BlockBase;
  use Drupal\Core\Block\BlockPluginInterface;
  use Drupal\Core\Form\FormStateInterface;
  use Abraham\TwitterOAuth\TwitterOAuth;

  /**
   * Provides 'TwitterBlock' Block.
   *
   * @Block(
   *   id = "agid_twitter_block",
   *   admin_label = @Translation("TwitterBlock"),
   * )
   */
  class TwitterBlock extends BlockBase implements BlockPluginInterface {

    /**
     * Implements blockForm
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *
     * @return array
     */
    public function blockForm($form, FormStateInterface $form_state) {
      $form = parent::blockForm($form, $form_state);
      // Default settings
      $config = $this->getConfiguration();
      // Title to show
      $form['title'] = [
        '#type' => 'textfield',
        '#title' => 'Title to show',
        '#default_value' => isset($config['title']) ? $config['title'] : '',
        '#description' => $this->t('Block\'s title (es: Twitter of @AgidGov)'),
        '#required' => false,
      ];
      // Username field
      $form['username'] = [
        '#type' => 'textfield',
        '#title' => 'Twitter User',
        '#description' => $this->t('Twitter username (es: AgidGov)'),
        '#default_value' => isset($config['username']) ? $config['username'] : 'AgidGov',
        '#required' => true,
      ];
      // Tweets to show
      $form['tweets_to_show'] = [
        '#type' => 'number',
        '#title' => 'Tweets to show',
        '#description' => $this->t('Default it\'s 3'),
        '#default_value' => isset($config['tweets_to_show']) ? $config['tweets_to_show'] : 3,
        '#required' => false,
      ];
      /* Twitter API */
      // Consumer Key
      $form['twitter_app'] = [
        '#type' => 'details',
        '#title' => $this->t('Twitter App'),
      ];
      $form['twitter_app']['consumer_key'] = [
        '#type' => 'textfield',
        '#title' => 'Twitter Consumer Key (API Key)',
        '#default_value' => isset($config['consumer_key']) ? $config['consumer_key'] : '',
        '#required' => true,
      ];
      // Consumer Secret
      $form['twitter_app']['consumer_secret'] = [
        '#type' => 'textfield',
        '#title' => 'Twitter Consumer Secret (API Secret)',
        '#default_value' => isset($config['consumer_secret']) ? $config['consumer_secret'] : '',
        '#required' => true,
      ];
      // Application Token
      $form['twitter_app']['access_token'] = [
        '#type' => 'textfield',
        '#title' => 'Twitter Access Token',
        '#default_value' => isset($config['access_token']) ? $config['access_token'] : '',
        '#required' => true,
      ];
      // Application Token Secret
      $form['twitter_app']['access_token_secret'] = [
        '#type' => 'textfield',
        '#title' => 'Twitter Access Token Secret',
        '#default_value' => isset($config['access_token_secret']) ? $config['access_token_secret'] : '',
        '#required' => true,
      ];
      return $form;
    }

    /**
     * Save block form
     * @param array $form
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
      parent::blockSubmit($form, $form_state);
      // Get twitter api keys
      $twitter_app = $form_state->getValue('twitter_app');
      // Save configuration
      $this->configuration['title'] = $form_state->getValue('title');
      $this->configuration['username'] = $form_state->getValue('username');
      $this->configuration['tweets_to_show'] = $form_state->getValue('tweets_to_show');
      $this->configuration['consumer_key'] = $twitter_app['consumer_key'];
      $this->configuration['consumer_secret'] = $twitter_app['consumer_secret'];
      $this->configuration['access_token'] = $twitter_app['access_token'];
      $this->configuration['access_token_secret'] = $twitter_app['access_token_secret'];
    }

    /**
     * Render block
     * @return array
     */
    public function build() {
      $config = $this->getConfiguration();
      // TwitterOAuth
      $connection = new TwitterOAuth($config['consumer_key'], $config['consumer_secret'], $config['access_token'], $config['access_token_secret']);
      $tweets = $connection->get("statuses/user_timeline", ["screen_name" => $config['username'], "count" => $config['tweets_to_show']*2, "exclude_replies" => true]);
      $tweets = array_slice($tweets, 0, $config['tweets_to_show']);
      return [
        '#theme' => 'agid_twitter_block',
        '#title' => $config['title'],
        '#username' => $config['username'],
        '#tweets_to_show' => $config['tweets_to_show'],
        '#tweets' => $tweets,
      ];
    }
  }