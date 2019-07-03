 <?php

/**
 * @file
 * Contains \Drupal\custom_search_functionality\Controller\CustomSearchFunctionalityController.
 */
namespace Drupal\custom_search_functionality\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;


class CustomSearchFunctionalityController extends ControllerBase {
  public function content($name) {
		$ajax_response = new AjaxResponse();
		global $base_url;
		// Get keyword from URL.
		$keyword_value_url = \Drupal::request()->query->get('keyword');
		$database = \Drupal::database();
		
		// Query to get data.
		$result = $database->select('node_field_data', 'n')
			->fields('n', ['title','nid'])
			->condition('title', '%' . db_like($keyword_value_url) . '%', 'LIKE')
			->distinct()
			->execute()
			->fetchAll();
			
		$ul_start = '<ul style="list-style-type:none; !important">';
		foreach($result as $result_array){
			$nid_reference = $result_array->nid;
			$title_reference = $result_array->title;
			$href_generate = $base_url.'/gu/node/'.$nid_reference;
			$reference[] ='<li style="padding-bottom:2px;border-bottom:2px solid white;"><a href='.$href_generate.'>'.$title_reference.'</a></li>';
			//print_r($reference);
		}
		if(empty($result)) {
			$reference[] ='<li style="padding-bottom:2px;border-bottom:2px solid white;"><a style="cursor: auto;">No Data Found</a></li>';
		}
		$ul_end = '</ul>';
		
		// Query result to get data based on keyword.
		$data_implode = implode('',$reference);
		
		// Code to render form in drupal 8.
		$search_form_get = \Drupal::formBuilder()->getForm('Drupal\custom_search_functionality\Form\CustomSearchFunctionalityForm');
		$search_form_render = \Drupal::service('renderer')->render($search_form_get);		
		
		// Markup to combine array of Form and Result of Search.
		$myForm = $this->formBuilder()->getForm('Drupal\custom_search_functionality\Form\CustomSearchFunctionalityForm');
        $renderer = \Drupal::service('renderer');
        $myFormHtml = $renderer->render($myForm);
		$current_url = Url::fromRoute('<current>');
		$path = $current_url->toString();
		$path_explode = explode('/',$path);
		$get_custom = \Drupal::request()->query;		
		$ajax_response->addCommand(new HtmlCommand('#search-result', $data_implode));
		return $ajax_response;
		
		if($path_explode[2] == 'custom_search_render'){
			if(sizeof($get_custom) == 0)
			{
				return [
					'#markup' => Markup::create("
						<div class='custom_search_overlay' style='position: fixed;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(0,0,0,0.8);z-index: 2;'>
						<div class='search_form_data' style='text-align: center;display: contents;color: white;'>
						<h2>Fill keyword to Search</h2>
						{$myFormHtml}
						</div>
						</div>
					")
				];
			}
			else {
				return [
					'#markup' => Markup::create("
						<div class='custom_search_overlay' style='position: fixed;width: 100%;height: 100%;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(0,0,0,0.8);z-index: 2;'>
						<div class='search_form_data' style='text-align: center;display: contents;color: white;'>
						<h2>Fill keyword to Search</h2>
						{$myFormHtml}
						<h2 style='padding-bottom:5px;border-bottom: 2px solid white;'>Searched Result</h2>
						{$ul_start}
						{$data_implode}
						{$ul_end}
						</div>
						</div>
					")
				];
			}
		}
			
  }
}