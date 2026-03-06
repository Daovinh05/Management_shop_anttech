package com.techzone.banhang.repository;

import com.techzone.banhang.entity.GioHang;
import com.techzone.banhang.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface GioHangRepository extends JpaRepository<GioHang, String> {

    Optional<GioHang> findByUser(User user);

    Optional<GioHang> findByUserMaUser(String maUser);
}
