package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

import java.math.BigDecimal;
import java.util.List;

@Entity
@Table(name = "bien_the")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class BienThe {

    @Id
    @Column(name = "ma_bien_the", length = 20)
    private String maBienThe;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_san_pham", nullable = false)
    private SanPham sanPham;

    @Column(name = "ten_bien_the", length = 55)
    private String tenBienThe;

    @Column(name = "img_bien_the", length = 255)
    private String imgBienThe;

    @Column(name = "mau_sac", length = 50)
    private String mauSac;

    @Column(name = "ram", length = 50)
    private String ram;

    @Column(name = "dung_luong", length = 50)
    private String dungLuong;

    @Column(name = "gia", precision = 15, scale = 2)
    private BigDecimal gia;

    @Column(name = "so_luong_kho")
    private Integer soLuongKho = 0;

    @OneToMany(mappedBy = "bienThe", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<ChiTietGioHang> chiTietGioHangList;

    @OneToMany(mappedBy = "bienThe", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<ChiTietDonHang> chiTietDonHangList;
}
