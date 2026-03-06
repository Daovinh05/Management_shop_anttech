package com.techzone.banhang.repository;

import com.techzone.banhang.entity.SanPham;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface SanPhamRepository extends JpaRepository<SanPham, String> {

    @Query("SELECT s FROM SanPham s WHERE s.maSanPham LIKE %:maSanPham% AND s.tenSanPham LIKE %:tenSanPham%")
    List<SanPham> findByMaAndTen(@Param("maSanPham") String maSanPham, @Param("tenSanPham") String tenSanPham);

    List<SanPham> findByDanhMucMaDanhMuc(String maDanhMuc);

    @Query("SELECT s FROM SanPham s JOIN s.danhMuc dm JOIN s.thuongHieu th WHERE " +
           "(:maDanhMuc IS NULL OR :maDanhMuc = '' OR :maDanhMuc = 'tat-ca' OR dm.maDanhMuc = :maDanhMuc) AND " +
           "(:maThuongHieu IS NULL OR :maThuongHieu = '' OR :maThuongHieu = 'tat-ca' OR th.maThuongHieu = :maThuongHieu)")
    List<SanPham> filterByCategoryAndBrand(
        @Param("maDanhMuc") String maDanhMuc,
        @Param("maThuongHieu") String maThuongHieu
    );

    Page<SanPham> findAll(Pageable pageable);

    @Query("SELECT COUNT(s) FROM SanPham s")
    long getTotalCount();
}
