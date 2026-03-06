package com.techzone.banhang.controller;

import com.techzone.banhang.service.SanPhamService;
import com.techzone.banhang.service.UserService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
@RequestMapping("/admin")
@RequiredArgsConstructor
public class AdminController {

    private final SanPhamService sanPhamService;
    private final UserService userService;

    @GetMapping
    public String adminDashboard(Model model) {
        model.addAttribute("page", "admin-dashboard");
        model.addAttribute("totalProducts", sanPhamService.getTotalCount());
        model.addAttribute("totalUsers", userService.getAllUsers().size());
        return "admin/dashboard";
    }
}
