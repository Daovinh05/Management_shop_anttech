package com.techzone.banhang.service;

import com.techzone.banhang.entity.SanPham;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

import java.util.List;
import java.util.Optional;

public interface SanPhamService {

    List<SanPham> getAllSanPham();

    Page<SanPham> getAllSanPham(Pageable pageable);

    Optional<SanPham> getSanPhamById(String id);

    SanPham createSanPham(SanPham sanPham);

    SanPham updateSanPham(SanPham sanPham);

    void deleteSanPham(String id);

    List<SanPham> findByMaAndTen(String maSanPham, String tenSanPham);

    List<SanPham> findByDanhMuc(String maDanhMuc);

    long getTotalCount();
}
