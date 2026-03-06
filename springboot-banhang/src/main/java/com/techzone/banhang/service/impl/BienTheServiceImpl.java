package com.techzone.banhang.service.impl;

import com.techzone.banhang.entity.BienThe;
import com.techzone.banhang.repository.BienTheRepository;
import com.techzone.banhang.service.BienTheService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional
public class BienTheServiceImpl implements BienTheService {

    private final BienTheRepository bienTheRepository;

    @Override
    @Transactional(readOnly = true)
    public List<BienThe> getAllBienThe() {
        return bienTheRepository.findAll();
    }

    @Override
    @Transactional(readOnly = true)
    public BienThe getBienTheById(String id) {
        return bienTheRepository.findById(id).orElseThrow();
    }

    @Override
    @Transactional(readOnly = true)
    public List<BienThe> findBySanPham(String maSanPham) {
        return bienTheRepository.findBySanPhamMaSanPham(maSanPham);
    }

    @Override
    public BienThe createBienThe(BienThe bienThe) {
        return bienTheRepository.save(bienThe);
    }

    @Override
    public BienThe updateBienThe(BienThe bienThe) {
        return bienTheRepository.save(bienThe);
    }

    @Override
    public void deleteBienThe(String id) {
        bienTheRepository.deleteById(id);
    }
}
