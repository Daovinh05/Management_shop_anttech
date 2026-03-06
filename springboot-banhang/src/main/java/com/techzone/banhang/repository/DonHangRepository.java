package com.techzone.banhang.repository;

import com.techzone.banhang.entity.DonHang;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface DonHangRepository extends JpaRepository<DonHang, String> {

    Page<DonHang> findByUserMaUser(String maUser, Pageable pageable);
}
