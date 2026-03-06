package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

import java.math.BigDecimal;

@Entity
@Table(name = "chi_tiet_don_hang")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
@IdClass(ChiTietDonHangId.class)
public class ChiTietDonHang {

    @Id
    @Column(name = "ma_ctdh", length = 20)
    private String maCtdh;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_don_hang", nullable = false)
    private DonHang donHang;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_bien_the", nullable = false)
    private BienThe bienThe;

    @Column(name = "so_luong")
    private Integer soLuong;

    @Column(name = "gia_luc_mua", precision = 15, scale = 2)
    private BigDecimal giaLucMua;
}
