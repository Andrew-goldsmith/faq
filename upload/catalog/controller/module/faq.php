<?php
class ControllerModuleFaq extends Controller {

	public function index() {
		$this->load->language('module/faq');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_faq_heading'] = $this->language->get('text_faq_heading');

		$data['text_faq_description'] = $this->language->get('text_faq_description');

		$data['text_no_comments'] = $this->language->get('text_no_comments');

		$data['entry_name'] = $this->language->get('entry_name');

		$data['entry_email'] = $this->language->get('entry_email');

		$data['entry_question'] = $this->language->get('entry_question');

		$data['text_submit'] = $this->language->get('text_submit');

		$this->load->model('module/faq');

		$questions = $this->model_module_faq->getQuestions();

		$id = 0;

		foreach ($questions as $question) {
			$id ++;
			$data['questions'][] = array(
				'question_text' => $question['question_name'],
				'answer_text' => html_entity_decode($question['answer'], ENT_QUOTES, 'UTF-8'),
				'id' => $id
			);
		}

		$data['customer_firstname'] = false;

		$data['logged'] = $this->customer->isLogged();

		if($this->customer->isLogged())  {
			$data['customer_firstname'] = $this->customer->getFirstName();
			$data['customer_email'] = $this->customer->getEmail();
		}

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq.css');
		}

		if (file_exists('catalog/view/javascript/faq.js')) {
			$this->document->addScript('catalog/view/javascript/faq.js');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/faq.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/faq.tpl', $data);
		}
	}

	public function sendQuestion() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			$this->load->language('module/faq');

			$json = array();

			if ((utf8_strlen($this->request->post['question_user_name']) < 3) || (utf8_strlen($this->request->post['question_user_name']) > 32)) {
				$json['error']['question_user_name'] = $this->language->get('text_error_name');
			}

			if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['question_email'])) {
				$json['error']['question_email'] = $this->language->get('text_error_email');
			}

			if ((utf8_strlen($this->request->post['question_text']) < 10) || (utf8_strlen($this->request->post['question_text']) > 2500)) {
				$json['error']['question_text'] = $this->language->get('text_error_question');
			}

			if(!isset($json['error'])) {

				if($this->config->get('faq_notice_status')) {
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					if($this->config->get('faq_default_email')) {
						$mail->setTo($this->config->get('config_email'));
					} else {
						$mail->setTo($this->config->get('faq_notice_email'));
					}
					$mail->setFrom($this->request->post['question_email']);
					$mail->setSender(html_entity_decode($this->request->post['question_user_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['question_user_name']), ENT_QUOTES, 'UTF-8'));
					$mail->setText($this->request->post['question_text']);
					$mail->send();
				}

				$this->load->model('module/faq');
				$this->model_module_faq->addQuestion($this->request->post);

				$json['success'] =  $this->language->get('text_success');
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
}