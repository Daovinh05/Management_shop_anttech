package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "users")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class User {

    @Id
    @Column(name = "ma_user", length = 20)
    private String maUser;

    @Column(name = "ten_user", length = 100)
    private String tenUser;

    @Column(name = "email", length = 100)
    private String email;

    @Column(name = "mat_khau", length = 255)
    private String matKhau;

    @Column(name = "so_dien_thoai", length = 20)
    private String soDienThoai;

    @Column(name = "dia_chi", length = 255)
    private String diaChi;

    @Column(name = "phan_quyen", length = 20)
    private String phanQuyen; // admin, nhan_vien, khach_hang

    @Column(name = "ngay_tao")
    @CreationTimestamp
    private LocalDateTime ngayTao;

    @Column(name = "trang_thai")
    private Boolean trangThai = true;
}
