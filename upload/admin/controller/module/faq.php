<?php
class ControllerModuleFaq extends Controller {
	private $error = array();

	public function index() {

		$this->language->load('module/faq');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('faq', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getLists();

	}

	public function add() {

		$this->language->load('module/faq');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/faq');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_module_faq->addQuestion($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_insert');

			$this->response->redirect($this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('module/faq');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/faq');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_faq->editQuestion($this->request->get['question_id'], $this->request->post);

			if(isset($this->request->post['answer']) && isset($this->request->post['answer_to_user'])) {
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setTo($this->request->post['answer']);
				if($this->config->get('faq_default_email')) {
					$mail->setFrom($this->config->get('config_email'));
				} else {
					$mail->setFrom($this->config->get('faq_notice_email'));
				}
				$mail->setSender('Strongler', ENT_QUOTES, 'UTF-8');
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'),$this->url->link('', 'SSL')), ENT_QUOTES, 'UTF-8'));
				$mail->setText($this->request->post['answer_to_user']);
				$mail->send();
			}

			$this->session->data['success'] = $this->language->get('text_success_update');

			$this->response->redirect($this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('module/faq');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/faq');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $question_id) {
				$this->model_module_faq->deleteQuestion($question_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_delete');

			$this->response->redirect($this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getLists();
	}

	private function getLists() {

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_modules'] = $this->language->get('text_modules');

		$data['text_insert'] = $this->language->get('text_insert');

		$data['text_delete'] = $this->language->get('text_delete');

		$data['text_cancel'] = $this->language->get('text_cancel');

		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_management_questions'] = $this->language->get('text_management_questions');

		$data['text_management_settings'] = $this->language->get('text_management_settings');

		$data['column_question'] = $this->language->get('column_question');

		$data['column_answer'] = $this->language->get('column_answer');

		$data['column_sort_order'] = $this->language->get('column_sort_order');

		$data['column_action'] = $this->language->get('column_action');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['entry_module_status'] = $this->language->get('entry_module_status');

		$data['entry_notice_status'] = $this->language->get('entry_notice_status');

		$data['entry_default_email'] = $this->language->get('entry_default_email');

		$data['entry_notice_email'] = $this->language->get('entry_notice_email');

		$data['text_save_settings'] = $this->language->get('text_save_settings');

		$data['text_enabled'] = $this->language->get('text_enabled');

		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_modules'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action_insert']		= $this->url->link('module/faq/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['action_delete']		= $this->url->link('module/faq/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['action_cancel']		= $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['action_settings']	= $this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('module/faq');

		$data['questions_nav'] = array();

		$data['questions_nav']['new'] = array(
			'name'	=> $this->language->get('text_new_questions'),
			'total'	=> $this->model_module_faq->getTotalQuestions("new_status")
		);

		$data['questions_nav']['published'] = array(
			'name'	=> $this->language->get('text_published_questions'),
			'total'	=> $this->model_module_faq->getTotalQuestions("publish_status"),
			'info'	=> $this->language->get('text_published_info')
		);

		$data['questions_nav']['answered'] = array(
			'name'	=> $this->language->get('text_answered_questions'),
			'total'	=> $this->model_module_faq->getTotalQuestions("answered_status"),
			'info'	=> $this->language->get('text_answered_info')
		);

		$data['questions'] = array();

		$data['questions']['new'] = array();

		$questions_new = $this->model_module_faq->getQuestions("new_status");

		foreach ($questions_new as $question_new) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/faq/update', 'token=' . $this->session->data['token'] . '&question_id=' . $question_new['question_id'], 'SSL')
			);

			$data['questions']['new'][] = array(
				'question_id'		=> $question_new['question_id'],
				'name'  			=> $question_new['question_name'],
				'sort_order'		=> $question_new['sort_order'],
				'selected'			=> isset($this->request->post['selected']) && in_array($question_new['question_id'], $this->request->post['selected']),
				'action'			=> $action
			);
		}

		$questions_published = $this->model_module_faq->getQuestions("publish_status");

		$data['questions']['published'] = array();

		foreach ($questions_published as $question_published) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/faq/update', 'token=' . $this->session->data['token'] . '&question_id=' . $question_published['question_id'], 'SSL')
			);

			$data['questions']['published'][] = array(
				'question_id'		=> $question_published['question_id'],
				'name'  			=> $question_published['question_name'],
				'sort_order'		=> $question_published['sort_order'],
				'selected'			=> isset($this->request->post['selected']) && in_array($question_published['question_id'], $this->request->post['selected']),
				'action'			=> $action
			);
		}

		$questions_answered = $this->model_module_faq->getQuestions("answered_status");

		$data['questions']['answered'] = array();

		foreach ($questions_answered as $question_answered) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/faq/update', 'token=' . $this->session->data['token'] . '&question_id=' . $question_answered['question_id'], 'SSL')
			);

			$data['questions']['answered'][] = array(
				'question_id'		=> $question_answered['question_id'],
				'name'  			=> $question_answered['question_name'],
				'sort_order'		=> $question_answered['sort_order'],
				'selected'			=> isset($this->request->post['selected']) && in_array($question_answered['question_id'], $this->request->post['selected']),
				'action'			=> $action
			);

		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['faq_notice_status'])) {
			$data['faq_notice_status'] = $this->request->post['faq_notice_status'];
		} else {
			$data['faq_notice_status'] = $this->config->get('faq_notice_status');
		}

		if (isset($this->request->post['faq_default_email'])) {
			$data['faq_default_email'] = $this->request->post['faq_default_email'];
		} else {
			$data['faq_default_email'] = $this->config->get('faq_default_email');
		}

		$data['notice_email_config'] = $this->config->get('config_email');
		$data['notice_email_custom'] = $this->config->get('faq_notice_email');

		if (isset($this->request->post['faq_status'])) {
			$data['faq_status'] = $this->request->post['faq_status'];
		} else {
			$data['faq_status'] = $this->config->get('faq_status');
		}

		if (isset($this->request->post['faq_notice_email'])) {
			$data['faq_notice_email'] = $this->request->post['faq_notice_email'];
		} else {
			if(!isset($this->request->post['faq_default_email'])) {
				$data['faq_notice_email'] = $this->config->get('faq_notice_email');
			} else {
				$data['faq_notice_email'] = $this->config->get('config_email');
			}

		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/faq_list.tpl', $data));
	}

	private function getForm() {

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');

		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['text_select_all'] = $this->language->get('text_select_all');

		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['text_question'] = $this->language->get('text_question');

		$data['text_answer'] = $this->language->get('text_answer');

		$data['entry_question'] = $this->language->get('entry_question');

		$data['entry_answer'] = $this->language->get('entry_answer');

		$data['entry_answer_to_user'] = $this->language->get('entry_answer_to_user');

		$data['entry_content'] = $this->language->get('entry_content');

		$data['entry_quetion'] = $this->language->get('entry_question');

		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['entry_product'] = $this->language->get('entry_product');

		$data['button_save'] = $this->language->get('button_save');

		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_label'] = $this->language->get('text_label');

		$data['text_edit_guestion'] = $this->language->get('text_edit_question');

		$data['text_interested_email'] = $this->language->get('text_interested_email');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['answer'])) {
			$data['error_answer'] = $this->error['answer'];
		} else {
			$data['error_answer'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['question_id'])) {
			$data['action'] = $this->url->link('module/faq/add', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/faq/update', 'token=' . $this->session->data['token'] . '&question_id=' . $this->request->get['question_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('module/faq', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['question_id'])) {
			$question_info = $this->model_module_faq->getQuestion($this->request->get['question_id']);

			if($question_info['interested_email'] && $question_info['answered_status'] == 0) {
				$data['interested_email'] = $question_info['interested_email'];
				$data['with_email'] = true;
				$data['text_send'] = $this->language->get('text_send');
				$data['action'] = $this->url->link('module/faq/update', 'token=' . $this->session->data['token'] . '&question_id=' . $this->request->get['question_id'], 'SSL');
			} else {
				$data['with_email'] = false;
			}
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['question_description'])) {
			$data['question_description'] = $this->request->post['question_description'];
		} elseif (isset($this->request->get['question_id'])) {
			$data['question_description'] = $this->model_module_faq->getQuestionDescriptions($this->request->get['question_id']);
		} else {
			$data['question_description'] = array();
		}
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($question_info)) {
			$data['sort_order'] = $question_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['publish_status'])) {
			$data['publish_status'] = $this->request->post['publish_status'];
		} elseif (!empty($question_info)) {
			$data['publish_status'] = $question_info['publish_status'];
		} else {
			$data['publish_status'] = 1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/faq_form.tpl', $data));

	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/faq')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['question_description'] as $language_id => $value) {

			if ((utf8_strlen($value['question']) < 2) || (utf8_strlen($value['question']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if ((utf8_strlen($value['answer']) < 2) || (utf8_strlen($value['answer']) > 3000)) {
				$this->error['answer'][$language_id] = $this->language->get('error_answer');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/faq')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/custom_footer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "faq` (
		  `question_id` int(11) NOT NULL AUTO_INCREMENT,
		  `sort_order` int(3) NOT NULL DEFAULT '0',
		  `new_status` tinyint(1) NOT NULL,
		  `answered_status` tinyint(1) NOT NULL,
		  `publish_status` tinyint(1) NOT NULL,
		  `interested_email` varchar(255) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`question_id`)
		)");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "faq_description` (
		  `question_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `question_name` varchar(64) COLLATE utf8_bin NOT NULL,
		  `answer` TEXT COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`question_id`,`language_id`)
		)");
	}
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "faq`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "faq_description`");
	}
}
