package com.techzone.banhang.service;

import com.techzone.banhang.entity.GioHang;

import java.util.List;
import java.util.Optional;

public interface GioHangService {
    List<GioHang> getAllGioHang();
    Optional<GioHang> getGioHangById(String id);
    Optional<GioHang> getGioHangByUser(String maUser);
    GioHang createGioHang(GioHang gioHang);
    GioHang updateGioHang(GioHang gioHang);
    void deleteGioHang(String id);
}
