package com.techzone.banhang.controller;

import com.techzone.banhang.entity.SanPham;
import com.techzone.banhang.service.DanhMucService;
import com.techzone.banhang.service.SanPhamService;
import com.techzone.banhang.service.ThuongHieuService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/admin/sanpham")
@RequiredArgsConstructor
public class AdminSanPhamController {

    private final SanPhamService sanPhamService;
    private final DanhMucService danhMucService;
    private final ThuongHieuService thuongHieuService;

    @GetMapping
    public String danhSach(Model model) {
        model.addAttribute("dulieu", sanPhamService.getAllSanPham());
        model.addAttribute("page", "sanpham-list");
        return "admin/sanpham/list";
    }

    @GetMapping("/them")
    public String themMoi(Model model) {
        model.addAttribute("sanpham", new SanPham());
        model.addAttribute("dsdm", danhMucService.getAllDanhMuc());
        model.addAttribute("dsth", thuongHieuService.getAllThuongHieu());
        model.addAttribute("page", "sanpham-form");
        return "admin/sanpham/form";
    }

    @PostMapping("/ins")
    public String insert(@ModelAttribute SanPham sanPham, RedirectAttributes redirectAttributes) {
        sanPhamService.createSanPham(sanPham);
        redirectAttributes.addFlashAttribute("success", "Thêm mới thành công!");
        return "redirect:/admin/sanpham";
    }

    @GetMapping("/sua/{id}")
    public String sua(@PathVariable String id, Model model) {
        SanPham sanPham = sanPhamService.getSanPhamById(id).orElseThrow();
        model.addAttribute("sanpham", sanPham);
        model.addAttribute("dsdm", danhMucService.getAllDanhMuc());
        model.addAttribute("dsth", thuongHieuService.getAllThuongHieu());
        model.addAttribute("page", "sanpham-form");
        return "admin/sanpham/form";
    }

    @PostMapping("/update")
    public String update(@ModelAttribute SanPham sanPham, RedirectAttributes redirectAttributes) {
        sanPhamService.updateSanPham(sanPham);
        redirectAttributes.addFlashAttribute("success", "Cập nhật thành công!");
        return "redirect:/admin/sanpham";
    }

    @GetMapping("/xoa/{id}")
    public String xoa(@PathVariable String id, RedirectAttributes redirectAttributes) {
        sanPhamService.deleteSanPham(id);
        redirectAttributes.addFlashAttribute("success", "Xóa thành công!");
        return "redirect:/admin/sanpham";
    }
}
