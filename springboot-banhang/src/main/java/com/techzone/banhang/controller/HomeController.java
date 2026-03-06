package com.techzone.banhang.controller;

import com.techzone.banhang.service.DanhMucService;
import com.techzone.banhang.service.SanPhamService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
@RequiredArgsConstructor
public class HomeController {

    private final SanPhamService sanPhamService;
    private final DanhMucService danhMucService;

    @GetMapping("/")
    public String index() {
        return "redirect:/home";
    }

    @GetMapping("/home")
    public String home(Model model) {
        model.addAttribute("page", "home");
        model.addAttribute("dssp", sanPhamService.getAllSanPham());
        model.addAttribute("dsdm", danhMucService.getAllDanhMuc());
        return "home";
    }
}
