<?php
/**
 * @file
 * Contains \Drupal\site_customisation\BasicPageJson.
 * Returns the JSON of basic page node type
 */

namespace Drupal\site_customisation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class BasicPageJson extends ControllerBase {

  public function returnJson() {
		// Get the current page arguments
		$path = \Drupal::request()->getpathInfo();
  	$arg = explode('/',$path); 
  	// Get the Site API key
  	$get_site_api_key = \Drupal::config('site_customisation.settings')->get('site_api_key');

  	if (($arg[1] == 'node') && is_numeric($arg[2])) { 
  		// Get the current node object
  		$node = \Drupal\node\Entity\Node::load($arg[2]);

  		// Check if Content Type is "Basic Page & Correct site api key is entered"
  		if ($node->bundle() == 'page' && ($arg[3] == $get_site_api_key)) {
  			// Generates the JSON of node
 				$serializer = \Drupal::service('serializer');
				$data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
				$response = new JsonResponse();
				$response->setData($data);
				return $response;
  		} 
  		// Redirects to access denied page
  		else {
	  		$url = Url::fromRoute('system.403');
				$response = new RedirectResponse($url->toString());
				$response->send();
				return;
			}
		}
	}
}
