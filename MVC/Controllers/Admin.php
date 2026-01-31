<?php
class Admin extends controller
{
    function Get_data()
    {
        // Hiển thị trang chủ admin
        $this->view('Master', [
            'page' => 'admin'
        ]);
    }
}