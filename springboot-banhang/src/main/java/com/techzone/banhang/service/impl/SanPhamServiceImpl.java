package com.techzone.banhang.service.impl;

import com.techzone.banhang.entity.SanPham;
import com.techzone.banhang.repository.SanPhamRepository;
import com.techzone.banhang.service.SanPhamService;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class SanPhamServiceImpl implements SanPhamService {

    private final SanPhamRepository sanPhamRepository;

    @Override
    @Transactional(readOnly = true)
    public List<SanPham> getAllSanPham() {
        return sanPhamRepository.findAll();
    }

    @Override
    @Transactional(readOnly = true)
    public Page<SanPham> getAllSanPham(Pageable pageable) {
        return sanPhamRepository.findAll(pageable);
    }

    @Override
    @Transactional(readOnly = true)
    public Optional<SanPham> getSanPhamById(String id) {
        return sanPhamRepository.findById(id);
    }

    @Override
    public SanPham createSanPham(SanPham sanPham) {
        return sanPhamRepository.save(sanPham);
    }

    @Override
    public SanPham updateSanPham(SanPham sanPham) {
        return sanPhamRepository.save(sanPham);
    }

    @Override
    public void deleteSanPham(String id) {
        sanPhamRepository.deleteById(id);
    }

    @Override
    @Transactional(readOnly = true)
    public List<SanPham> findByMaAndTen(String maSanPham, String tenSanPham) {
        return sanPhamRepository.findByMaAndTen(maSanPham, tenSanPham);
    }

    @Override
    @Transactional(readOnly = true)
    public List<SanPham> findByDanhMuc(String maDanhMuc) {
        return sanPhamRepository.findByDanhMucMaDanhMuc(maDanhMuc);
    }

    @Override
    @Transactional(readOnly = true)
    public long getTotalCount() {
        return sanPhamRepository.getTotalCount();
    }
}
