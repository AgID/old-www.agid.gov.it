<?php
  /**
   * @file Declare Pdf utility class
   */
  namespace Drupal\webform_multistep\Pdf;

  use Drupal\Core\Entity\EntityStorageException;
  use \Drupal\file\Entity\File;
  use Dompdf\Dompdf;

  class Pdf {
    protected $module_config;

    private $dompdf;
    private $html = [];
    private $title = 'NOTITLE';
    private $drupalFile;
    protected $module_path;
    protected $css_template_file;

    public function __construct() {
      $this->dompdf = new Dompdf();
      $this->dompdf->setPaper('A4');

      $this->module_config = \Drupal::configFactory()->get('webform_multistep.adminsettings');

      $this->html['header'] = $this->module_config->get('pdf_header')['value'];
      $this->html['content'] = '';
      $this->html['footer'] = $this->module_config->get('pdf_footer')['value'];

      // Set module path variable
      $this->module_path = drupal_get_path('module', 'webform_multistep');
      // Add relative path (css file)
      $this->css_template_file = $this->module_path . '/src/Pdf/PDF_CSS_Template.php';

      return $this;
    }

    private function wrapHTML() {

      $loader = new \Twig\Loader\FilesystemLoader($this->module_path . '/src/Pdf');
      $twig = new \Twig\Environment($loader);
      $template = $twig->load('PDF_Template.twig');

      return $template->render([
        'css_template' => $this->css_template_file,
        'title' => $this->title,
        'header' => $this->html['header'],
        'footer' => $this->html['footer'],
        'content' => $this->html['content']
      ]);
    }

    public function setContent($c) {
      $this->html['content'] = $c;
    }

    /**
     * @return \Drupal\file\Entity\File;
     */
    public function getDrupalFile() {
      return $this->drupalFile;
    }

    /**
     * Clean (erase) the output buffer and turn off output buffering and
     * then offers to download file with $filename name.
     *
     * @param string $filename
     * @return Pdf
     */
    private function stream($filename = '') {
      $this->dompdf->render();
      ob_end_clean();
      $this->dompdf->stream($filename);
      return $this;
    }

    /**
     * Render PDF element
     */
    public function render() {
      // File name
      $filename = $this->module_config->get('pdf_filename');

      // Embeds the code in the correct html structure and then merges it into a single string
      $final_html = $this->wrapHTML();
      // Set pdf html content
      $this->dompdf->loadHtml($final_html);
      return $this;
    }

    /**
     * Save files in public folder and then create its as a managed file.
     */
    public function save($random_id) {
      $user = \Drupal::currentUser();
      // Render pdf
      $this->dompdf->render();
      // Get filename from settings
      $filename = $this->module_config->get('pdf_filename');
      $filename = isset($filename) ? $filename : 'document.pdf';
      $filename = str_replace('%', $random_id, $filename);
      $filepath = $this->module_config->get('pdf_filepath');
      $uri = $filepath . '/' . $filename;
      // Create File in DB
      try {
        $file = File::create([
          'uid' => $user->id(),
          'filename' => $filename,
          'uri' => $uri,
          'status' => 1,
        ]);
        $file->save();
        // Checks if exists file directory, if not create it
        $dir = dirname($file->getFileUri());
        file_prepare_directory($dir, FILE_CREATE_DIRECTORY);
        // Save dompdf contents to file... save file in the disk
        file_put_contents($file->getFileUri(), $this->dompdf->output());
        $file->save();
        // Add file usage
        $file_usage = \Drupal::service('file.usage');
        $file_usage->add($file, 'webform_multistep', 'user', $user->id());
        $file->save();
        // Assign file to drupalFile attribute
        $this->drupalFile = $file;
        return $this;
      } catch (EntityStorageException $e) {
        \Drupal\Core\Messenger\MessengerInterface::addMessage($e->getMessage());
        return $e;
      }
    }

  }
