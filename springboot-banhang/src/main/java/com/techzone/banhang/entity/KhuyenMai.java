package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "khuyen_mai")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class KhuyenMai {

    @Id
    @Column(name = "ma_khuyen_mai", length = 20)
    private String maKhuyenMai;

    @Column(name = "ten_khuyen_mai", length = 255, nullable = false)
    private String tenKhuyenMai;

    @Column(name = "mo_ta", length = 500)
    private String moTa;

    @Column(name = "gia_tri", precision = 15, scale = 2)
    private BigDecimal giaTri;

    @Column(name = "loai_khuyen_mai", length = 20)
    private String loaiKhuyenMai; // phan_tram, so_tien

    @Column(name = "ngay_bat_dau")
    private LocalDateTime ngayBatDau;

    @Column(name = "ngay_ket_thuc")
    private LocalDateTime ngayKetThuc;

    @Column(name = "trang_thai")
    private Boolean trangThai = true;
}
