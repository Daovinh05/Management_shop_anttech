package com.techzone.banhang.service.impl;

import com.techzone.banhang.entity.DanhMuc;
import com.techzone.banhang.repository.DanhMucRepository;
import com.techzone.banhang.service.DanhMucService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
@Transactional
public class DanhMucServiceImpl implements DanhMucService {

    private final DanhMucRepository danhMucRepository;

    @Override
    @Transactional(readOnly = true)
    public List<DanhMuc> getAllDanhMuc() {
        return danhMucRepository.findAll();
    }

    @Override
    @Transactional(readOnly = true)
    public DanhMuc getDanhMucById(String id) {
        return danhMucRepository.findById(id).orElseThrow();
    }

    @Override
    public DanhMuc createDanhMuc(DanhMuc danhMuc) {
        return danhMucRepository.save(danhMuc);
    }

    @Override
    public DanhMuc updateDanhMuc(DanhMuc danhMuc) {
        return danhMucRepository.save(danhMuc);
    }

    @Override
    public void deleteDanhMuc(String id) {
        danhMucRepository.deleteById(id);
    }
}
