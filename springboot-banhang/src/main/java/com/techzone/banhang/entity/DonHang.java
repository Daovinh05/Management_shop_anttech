package com.techzone.banhang.entity;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;

import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.List;

@Entity
@Table(name = "don_hang")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class DonHang {

    @Id
    @Column(name = "ma_don_hang", length = 20)
    private String maDonHang;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_user", nullable = false)
    private User user;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_dia_chi")
    private DiaChiGiaoHang diaChiGiaoHang;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "ma_khuyen_mai")
    private KhuyenMai khuyenMai;

    @Column(name = "ngay_dat")
    @CreationTimestamp
    private LocalDateTime ngayDat;

    @Column(name = "tong_tien", precision = 15, scale = 2)
    private BigDecimal tongTien;

    @Column(name = "trang_thai", length = 50)
    private String trangThai = "Moi"; // Moi, DaDuyet, DangGiao, HoanThanh, DaHuy

    @Column(name = "ghi_chu", length = 500)
    private String ghiChu;

    @Column(name = "ho_ten", length = 100)
    private String hoTen;

    @Column(name = "so_dien_thoai", length = 20)
    private String soDienThoai;

    @Column(name = "email", length = 100)
    private String email;

    @Column(name = "phuong_thuc_thanh_toan", length = 20)
    private String phuongThucThanhToan; // cod, bank

    @OneToMany(mappedBy = "donHang", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<ChiTietDonHang> chiTietDonHangList;
}
