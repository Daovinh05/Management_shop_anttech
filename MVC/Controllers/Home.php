<?php
class Home extends controller
{
    function Get_data()
    {
        // Hiển thị trang chủ admin
        $this->view('Master', [
            'page' => 'home'
        ]);
    }
}