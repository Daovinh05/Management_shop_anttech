package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "danh_gia")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class DanhGia {

    @Id
    @Column(name = "ma_danh_gia", length = 20)
    private String maDanhGia;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_user", nullable = false)
    private User user;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_san_pham", nullable = false)
    private SanPham sanPham;

    @Column(name = "so_sao")
    private Integer soSao;

    @Column(name = "noi_dung", length = 500)
    private String noiDung;

    @Column(name = "phan_hoi", length = 500)
    private String phanHoi;

    @Column(name = "ngay_danh_gia")
    @CreationTimestamp
    private LocalDateTime ngayDanhGia;
}
