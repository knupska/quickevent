<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(TOOLKIT . '/class.eventmanager.php');	
	
	class contentExtensionQuickEventEdit extends AdministrationPage {
		protected $_driver;
		private $_pages;
		
		public function __viewIndex() {
			$this->setPageType('form');
			$this->setTitle('Symphony &ndash; QuickEvent');
			
			$this->appendSubheading('QuickEvent');
			
			$this->_pages = $this->__getPages();
			$events = $this->__getEvents();
			
			if(count($this->_pages) > 0 && count($events) > 0) {
			
				foreach($events as $event) {
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					
					$total_selected = $this->__createPageList($group, $event['handle']);
					
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings');
					$container->appendChild(
						new XMLElement('legend', $event['name'] . ' (' . $total_selected . ')')
					);
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				
			} else {
			
				if (count($this->_pages) == 0) {
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings error');
					$container->appendChild(new XMLElement('legend', 'No Pages Found'));
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					$group->appendChild(Widget::Label('Please create some pages before using QuickEvent'));
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				
				if (count($events) == 0) {
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings error');
					$container->appendChild(new XMLElement('legend', 'No Events Found'));
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					$group->appendChild(Widget::Label('Please create some events before using QuickEvent'));
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				return;
				
			}
			
			$div = new XMLElement('div');
			$div->setAttribute('class', 'actions');
			$attr = array('accesskey' => 's');
			$div->appendChild(Widget::Input('action[save]', 'Save Changes', 'submit', $attr));
			$this->Form->appendChild($div);
		}
		
		public function __getPages() {
			return Symphony::Database()->fetch("
				SELECT
					p.*
				FROM
					tbl_pages AS p
				ORDER BY
					p.sortorder ASC
			");
		}
		
		public function __getEvents() {
			$EventManager = new EventManager($this->_Parent);
			return $EventManager->listAll();
		}
		
		public function __createPageList($context, $event) {
			$options = array();
			$total_selected = 0;
			foreach ($this->_pages as $page) {
				$selected = in_array($event, explode(',', $page['events']));
				if($selected) $total_selected++;
				
				$options[] = array(
					$page['id'], $selected, '/' . $this->_Parent->resolvePagePath($page['id'])
				);
			}
			
			$section = Widget::Label('Pages');
			$section->appendChild(Widget::Select(
				'settings[' . $event . '][]', $options, array(
					'multiple'	=> 'multiple'
				)
			));
			
			$context->appendChild($section);
			return $total_selected;
		}
		
		public function __actionIndex() {
			
			if (@isset($_POST['action']['save'])) {
			
				// extract the settings
				$settings  = @$_POST['settings'];
				
				// extract all the pages
				$this->_pages = $this->__getPages();
				
				// create an empty events array for each page
				$page_events = array();
				foreach($this->_pages as $page) $page_events[$page['id']] = array();
				
				// loop through the events and add to each page
				foreach($settings as $event => $pages) {
					foreach($pages as $page) $page_events[$page][] = $event;
				}
				
				// loop through the final events and add to the database
				$error = false;
				foreach($page_events as $page => $events) {
					
					// create the fields to be updated
					$fields = array('events' => @implode(',', $events));
					
					// update the fields
					if (!Symphony::Database()->update($fields, 'tbl_pages', "`id` = '$page'")) {
						$error = true;
						break;
					}
				}
				
				// show the success message
				if(!$error) {
					$this->pageAlert(
						__(
							'Events updated at %1$s.', 
							array(DateTimeObj::getTimeAgo(__SYM_TIME_FORMAT__))
						), 
						Alert::SUCCESS);
					return;
				}
				
				// show the error message
				$this->pageAlert(
					__(
						'Unknown errors occurred while attempting to save. Please check your <a href="%s">activity log</a>.',
						array(URL . '/symphony/system/log/')
					),
					Alert::ERROR);
			}
		}
	}
	
?>