package com.techzone.banhang.repository;

import com.techzone.banhang.entity.ChiTietDonHang;
import com.techzone.banhang.entity.ChiTietDonHangId;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ChiTietDonHangRepository extends JpaRepository<ChiTietDonHang, ChiTietDonHangId> {

    List<ChiTietDonHang> findByDonHangMaDonHang(String maDonHang);
}
