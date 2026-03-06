package com.techzone.banhang.repository;

import com.techzone.banhang.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<User, String> {

    Optional<User> findByTenUser(String tenUser);

    Optional<User> findByEmail(String email);

    boolean existsByTenUser(String tenUser);

    boolean existsByEmail(String email);

    boolean existsBySoDienThoai(String soDienThoai);

    @Query("SELECT u FROM User u WHERE u.tenUser = :username AND u.matKhau = :password")
    Optional<User> validateUser(@Param("username") String username, @Param("password") String password);
}
