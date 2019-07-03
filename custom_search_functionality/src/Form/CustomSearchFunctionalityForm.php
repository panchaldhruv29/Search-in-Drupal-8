<?php

namespace Drupal\custom_search_functionality\Form;

/**
 * @file
 * Provides custom_search_functionality functionality.
 */

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;


/**
 * Implements the youtube subscriber button form controller.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class CustomSearchFunctionalityForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_search_functionality_form';
  }

  /**
   * {@inheritdoc}
   */
   public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = array(
      '#type' => 'textfield',
	  '#attributes' => array('placeholder' => t('Search'),),
      '#required' => TRUE,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Search'),
	  '#ajax' => [
        'callback' => '::CustomSearchFunctionalityController',
		'event' => 'click'
      ],
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	global $base_url;
	$title_value = $form_state->getValue('title');
	$url_develop = $base_url.'/custom_search_render?keyword='.$title_value;
	$response = new RedirectResponse($url_develop);
	$response->send();
	/*$database = \Drupal::database();
	$result = $database->select('node_field_data', 'n')
		->fields('n', ['title','nid'])
		->condition('title', '%' . db_like($title_value) . '%', 'LIKE')
		->execute()
		->fetchAll();
	foreach($result as $result_array){
		$nid_reference = $result_array->nid;
		$title_reference = $result_array->title;
		$href_generate = $base_url.'/node/'.$nid_reference;
		$reference ='<a href='.$href_generate.'>'.$title_reference.'</a>';
		//print_r($reference);
	}*/
	
	
	
	//return $reference;
	//exit;
    //print_r($result);
	//exit;
	//$url_develop = $base_url.'/custom_search?keyword='.$title_value;
	//$response = new RedirectResponse($url_develop);
	//$response->send();
	
	//custom_get_url();
	//exit;
	//$current = $curr_uri = request_uri();
	//echo 5;
	//exit
	//$path = \Drupal::request()->query->get('keyword');
	//print_r($current);
	//exit;
	/*exit;
    foreach ($form_state->getValues() as $key => $value) {
		$url_develop = $base_url.'/custom_search?keyword='.$value;
		$response = new RedirectResponse($url_develop);
		$response->send();
    }
	echo 5	;
	$curr_uri = request_uri();
	$path = \Drupal::request()->query->get('keyword');
    echo 4;
	print_r($path);
	exit;*/
  }
   
  
}
