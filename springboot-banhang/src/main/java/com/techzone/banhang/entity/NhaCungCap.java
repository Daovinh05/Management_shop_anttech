package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "nha_cung_cap")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class NhaCungCap {

    @Id
    @Column(name = "ma_nha_cung_cap", length = 20)
    private String maNhaCungCap;

    @Column(name = "ten_nha_cung_cap", length = 255, nullable = false)
    private String tenNhaCungCap;

    @Column(name = "dia_chi", length = 255)
    private String diaChi;

    @Column(name = "so_dien_thoai", length = 20)
    private String soDienThoai;

    @Column(name = "email", length = 100)
    private String email;

    @Column(name = "ngay_tao")
    @CreationTimestamp
    private LocalDateTime ngayTao;
}
