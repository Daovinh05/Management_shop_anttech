package com.techzone.banhang.repository;

import com.techzone.banhang.entity.DiaChiGiaoHang;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface DiaChiGiaoHangRepository extends JpaRepository<DiaChiGiaoHang, String> {

    List<DiaChiGiaoHang> findByUserMaUser(String maUser);
}
