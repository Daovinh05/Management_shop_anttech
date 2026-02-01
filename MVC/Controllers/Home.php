<?php
class Home extends controller
{
    function Get_data()
    {
        //Hiển thị trang chủ chưa đăng nhập
        $this->view('Home_Master', [
            'page' => 'Home'
        ]);
    }
}