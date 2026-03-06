package com.techzone.banhang.repository;

import com.techzone.banhang.entity.KhuyenMai;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.time.LocalDateTime;
import java.util.List;

@Repository
public interface KhuyenMaiRepository extends JpaRepository<KhuyenMai, String> {

    @Query("SELECT k FROM KhuyenMai k WHERE k.trangThai = true AND " +
           "(k.ngayBatDau IS NULL OR k.ngayBatDau <= :now) AND " +
           "(k.ngayKetThuc IS NULL OR k.ngayKetThuc >= :now)")
    List<KhuyenMai> findAvailable(LocalDateTime now);
}
