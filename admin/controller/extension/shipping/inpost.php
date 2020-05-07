<?php

class ControllerExtensionShippingInpost extends Controller
{
    public function index() {
        $this->load->language('extension/shipping/inpost');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('shipping_inpost', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
        }



        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/inpost', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/shipping/inpost', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

        if (isset($this->request->post['shipping_inpost_cost'])) {
            $data['shipping_inpost_cost'] = $this->request->post['shipping_inpost_cost'];
        } else {
            $data['shipping_inpost_cost'] = $this->config->get('shipping_inpost_cost');
        }

        if(isset($this->request->post['shipping_inpost_mobile_size'])) {
            $data['shipping_inpost_mobile_size'] = $this->request->post['shipping_inpost_mobile_size'];
        } elseif ($this->config->get('shipping_inpost_mobile_size')) {
            $data['shipping_inpost_mobile_size'] = $this->config->get('shipping_inpost_mobile_size');
        }

        if(isset($this->request->post['shipping_inpost_api_endpoint'])) {
            $data['shipping_inpost_api_endpoint'] = $this->request->post['shipping_inpost_api_endpoint'];
        } elseif ($this->config->get('shipping_inpost_api_endpoint')) {
            $data['shipping_inpost_api_endpoint'] = $this->config->get('shipping_inpost_api_endpoint');
        }

        if (isset($this->request->post['shipping_inpost_status'])) {
            $data['shipping_inpost_status'] = $this->request->post['shipping_inpost_status'];
        } else {
            $data['shipping_inpost_status'] = $this->config->get('shipping_inpost_status');
        }

        if (isset($this->request->post['shipping_inpost_sort_order'])) {
            $data['shipping_inpost_sort_order'] = $this->request->post['shipping_inpost_sort_order'];
        } else {
            $data['shipping_inpost_sort_order'] = $this->config->get('shipping_inpost_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/inpost', $data));
    }

    private function validate() {

        if (!$this->user->hasPermission('modify', 'extension/shipping/inpost')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['shipping_inpost_api_endpoint']) {
            $this->error['state'] = $this->language->get('error_inpost_api_endpoint');
        }

        if (!$this->request->post['shipping_inpost_cost']) {
            $this->error['key'] = $this->language->get('error_inpost_cost');
        }

        return !$this->error;

    }
}