<?php

	class Extension_QuickEvent extends Extension {
	
		public function about() {
			return array(
				'name'			=> 'QuickEvent',
				'version'		=> '1.2',
				'release-date'	=> '2010-04-05',
				'author'		=> array(
					'name'			=> 'Nathan Martin',
					'website'		=> 'http://knupska.com',
					'email'			=> 'nathan@knupska.com'
				),
				'description'	=> 'Quickly add & remove events from pages.'
	 		);
		}
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/system/preferences/',
					'delegate'	=> 'AddCustomPreferenceFieldsets',
					'callback'	=> 'edit'
				),
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				)
			);
		}
		
		public function fetchNavigation() {
			return array(
				array(
					'location'	=> 'Blueprints',
					'name'	=> 'QuickEvent',
					'link'	=> '/edit/'
				)
			);
		}
		
		public function initaliseAdminPageHead($context) {
			$page = $context['parent']->Page;
			if ($page instanceof contentExtensionQuickEventEdit) {
				$page->addScriptToHead(URL . '/extensions/quickevent/assets/collapse_quickevent.js', 100000);
			}
			if ($page instanceof contentBlueprintsEvents) {
				$page->addScriptToHead(URL . '/extensions/quickevent/assets/shortcut_quickevent.js', 100000);
			}
		}
	}

?>