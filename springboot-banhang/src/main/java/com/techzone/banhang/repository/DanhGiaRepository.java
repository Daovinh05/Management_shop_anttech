package com.techzone.banhang.repository;

import com.techzone.banhang.entity.DanhGia;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface DanhGiaRepository extends JpaRepository<DanhGia, String> {

    List<DanhGia> findBySanPhamMaSanPhamOrderByNgayDanhGiaDesc(String maSanPham);

    @Query("SELECT AVG(dg.soSao) FROM DanhGia dg WHERE dg.sanPham.maSanPham = :maSanPham")
    Double getAvgRatingByProduct(@Param("maSanPham") String maSanPham);

    @Query("SELECT dg.soSao, COUNT(dg) FROM DanhGia dg WHERE dg.sanPham.maSanPham = :maSanPham GROUP BY dg.soSao")
    List<Object[]> getStarDistribution(@Param("maSanPham") String maSanPham);
}
