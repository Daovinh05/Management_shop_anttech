package com.techzone.banhang.repository;

import com.techzone.banhang.entity.BienThe;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface BienTheRepository extends JpaRepository<BienThe, String> {

    List<BienThe> findBySanPhamMaSanPham(String maSanPham);

    @Query("SELECT bt FROM BienThe bt WHERE bt.sanPham.maSanPham = :maSanPham ORDER BY bt.maBienThe")
    List<BienThe> findBySanPhamOrderByMaBienThe(@Param("maSanPham") String maSanPham);

    Optional<BienThe> findFirstBySanPhamMaSanPhamOrderByMaBienThe(String maSanPham);

    boolean existsByMaBienThe(String maBienThe);
}
