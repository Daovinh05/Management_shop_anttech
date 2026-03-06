package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "dia_chi_giao_hang")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class DiaChiGiaoHang {

    @Id
    @Column(name = "ma_dia_chi", length = 20)
    private String maDiaChi;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_user", nullable = false)
    private User user;

    @Column(name = "ho_ten", length = 100, nullable = false)
    private String hoTen;

    @Column(name = "so_dien_thoai", length = 20, nullable = false)
    private String soDienThoai;

    @Column(name = "email", length = 100)
    private String email;

    @Column(name = "dia_chi", length = 255, nullable = false)
    private String diaChi;

    @Column(name = "la_mac_dinh")
    private Boolean laMacDinh = false;
}
