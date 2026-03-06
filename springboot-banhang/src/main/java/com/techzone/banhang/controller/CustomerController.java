package com.techzone.banhang.controller;

import com.techzone.banhang.entity.*;
import com.techzone.banhang.service.*;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.*;

@Controller
@RequestMapping("/khachhang")
@RequiredArgsConstructor
public class CustomerController {

    private final SanPhamService sanPhamService;
    private final DanhMucService danhMucService;
    private final BienTheService bienTheService;
    private final GioHangService gioHangService;
    private final UserService userService;

    @GetMapping
    public String home(Model model,
                       @RequestParam(defaultValue = "1") int page,
                       @RequestParam(defaultValue = "12") int size) {
        model.addAttribute("page", "customer-home");
        model.addAttribute("dssp", sanPhamService.getAllSanPham());
        model.addAttribute("dsdm", danhMucService.getAllDanhMuc());
        return "customer/home";
    }

    @GetMapping("/sanpham/{maDanhMuc}")
    public String sanPhamTheoDanhMuc(@PathVariable String maDanhMuc, Model model) {
        model.addAttribute("page", "customer-products");
        model.addAttribute("dssp", sanPhamService.findByDanhMuc(maDanhMuc));
        model.addAttribute("dsdm", danhMucService.getAllDanhMuc());
        return "customer/products";
    }

    @GetMapping("/chitietsp/{maSanPham}")
    public String chiTietSanPham(@PathVariable String maSanPham, Model model) {
        SanPham sanPham = sanPhamService.getSanPhamById(maSanPham).orElseThrow();
        List<BienThe> bienTheList = bienTheService.findBySanPham(maSanPham);

        model.addAttribute("page", "customer-detail");
        model.addAttribute("san_pham", sanPham);
        model.addAttribute("bien_the", bienTheList);
        model.addAttribute("similar_products", sanPhamService.getAllSanPham().stream().limit(4).toList());
        return "customer/detail";
    }

    @PostMapping("/themvaogio/{maBienThe}")
    public String themVaoGio(@PathVariable String maBienThe,
                              @RequestParam(defaultValue = "1") int soLuong,
                              RedirectAttributes redirectAttributes) {
        // Implement cart logic here
        redirectAttributes.addFlashAttribute("success", "Đã thêm vào giỏ hàng!");
        return "redirect:/khachhang/chitietsp/" + maBienThe.split("_")[0];
    }

    @GetMapping("/giohang")
    public String gioHang(Model model) {
        model.addAttribute("page", "customer-cart");
        return "customer/cart";
    }

    @GetMapping("/thanhtoan")
    public String thanhToan(Model model) {
        model.addAttribute("page", "customer-checkout");
        return "customer/checkout";
    }
}
