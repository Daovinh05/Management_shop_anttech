package com.techzone.banhang.repository;

import com.techzone.banhang.entity.ChiTietGioHang;
import com.techzone.banhang.entity.ChiTietGioHangId;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ChiTietGioHangRepository extends JpaRepository<ChiTietGioHang, ChiTietGioHangId> {

    List<ChiTietGioHang> findByGioHangMaGioHang(String maGioHang);

    void deleteByGioHangMaGioHangAndBienTheMaBienThe(String maGioHang, String maBienThe);
}
