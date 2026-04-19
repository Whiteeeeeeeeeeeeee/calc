@extends('layouts.app')

@section('content')
<style>
    .main-card {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: none;
        background: white;
        margin-top: 20px;
    }
    .card-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        padding: 30px;
        border-bottom: none;
        text-align: center;
    }
    .loan-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        background: white;
        text-align: center;
        padding: 30px 20px;
        margin-bottom: 20px;
    }
    .loan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .loan-card.selected {
        border-color: #4361ee;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        box-shadow: 0 10px 30px rgba(67,97,238,0.3);
    }
    .loan-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #4361ee;
    }
    .loan-name {
        font-weight: bold;
        font-size: 1.3rem;
        margin-bottom: 10px;
    }
    .loan-rate {
        font-size: 0.9rem;
        color: #666;
    }
    .form-card {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 25px;
        margin-top: 20px;
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .result-card {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        text-align: center;
        margin-top: 20px;
    }
    .result-value {
        font-size: 1.8rem;
        font-weight: bold;
    }
    .btn-calculate {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 1.1rem;
        font-weight: bold;
        width: 100%;
        color: white;
    }
    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67,97,238,0.4);
    }
    .hidden {
        display: none;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .card-body {
        padding: 30px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card">
                <div class="card-header">
                <h3 class="mb-0 text-center text-white">Выберите тип кредита для расчета</h3>
                </div>
                
                <div class="card-body">
                    <!-- Только плитки с типами кредитов -->
                    <div class="row g-4">
                        @foreach($loans as $loan)
                        <div class="col-md-4">
                            <div class="loan-card" data-type="{{ $loan->type }}" data-rate="{{ $loan->annual_rate }}">
                                <div class="loan-icon">
                                    @if($loan->type == 'mortgage')
                                        <i class="fas fa-home"></i>
                                    @elseif($loan->type == 'auto')
                                        <i class="fas fa-car"></i>
                                    @else
                                        <i class="fas fa-shopping-cart"></i>
                                    @endif
                                </div>
                                <div class="loan-name">{{ $loan->name }}</div>
                                <div class="loan-rate">Ставка <strong>{{ number_format($loan->annual_rate, 2) }}%</strong></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="loan_type" id="loan_type" required>

                    <!-- Форма расчета (появляется после выбора плитки) -->
                    <div id="formContainer" class="hidden">
                        <div class="form-card">
                            <h4 class="mb-4 text-center" id="selectedLoanName"></h4>
                            
                            <form id="calculatorForm">
                                @csrf
                                <input type="hidden" name="loan_type" id="loan_type_input">
                                
                                <!-- Поля для ипотеки/автокредита -->
                                <div id="mortgageAutoFields">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Стоимость (₽)</label>
                                        <input type="number" class="form-control" id="property_cost" name="property_cost" >
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Первоначальный взнос (%)</label>
                                        <input type="number" class="form-control" id="down_payment_percent" name="down_payment_percent" min="0" max="90" placeholder="">
                                        <div id="downPaymentAmount" class="text-muted mt-1"></div>
                                    </div>
                                </div>

                                <!-- Поля для потребительского кредита -->
                                <div id="consumerFields" class="hidden">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Сумма кредита (₽)</label>
                                        <input type="number" class="form-control" id="loan_amount" name="loan_amount" >
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Срок кредита (месяцев)</label>
                                    <input type="number" class="form-control" id="term_months" name="term_months" >
                                    </div>

                                <button type="submit" class="btn-calculate" id="calculateBtn"> Рассчитать кредит
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Результаты -->
<div id="result" class="mt-4 hidden">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Результаты расчета</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded text-center" style="background-color: #E8F4FD;">
                        <small class="text-muted">Ежемесячный платеж</small>
                        <h3 class="text-primary mb-0" id="monthly_payment">0 ₽</h3>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded text-center" style="background-color: #E8FDE8;">
                        <small class="text-muted">Сумма кредита</small>
                        <h4 class="text-success mb-0" id="loan_amount_result">0 ₽</h4>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded text-center" style="background-color: #FFF3E0;">
                        <small class="text-muted">Общая выплата</small>
                        <h4 class="text-warning mb-0" id="total_payment">0 ₽</h4>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded text-center" style="background-color: #FEE8E8;">
                        <small class="text-muted">Переплата</small>
                        <h4 class="text-danger mb-0" id="overpayment">0 ₽</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loanCards = document.querySelectorAll('.loan-card');
        const formContainer = document.getElementById('formContainer');
        const loanTypeInput = document.getElementById('loan_type_input');
        const selectedLoanName = document.getElementById('selectedLoanName');
        const mortgageAutoFields = document.getElementById('mortgageAutoFields');
        const consumerFields = document.getElementById('consumerFields');
        const downPaymentInput = document.getElementById('down_payment_percent');
        const propertyCostInput = document.getElementById('property_cost');
        const downPaymentAmountDiv = document.getElementById('downPaymentAmount');
        const form = document.getElementById('calculatorForm');
        const calculateBtn = document.getElementById('calculateBtn');
        const resultDiv = document.getElementById('result');

        function formatNumber(num) {
            return new Intl.NumberFormat('ru-RU').format(Math.round(num));
        }

        function updateDownPaymentAmount() {
            if (propertyCostInput && propertyCostInput.value && downPaymentInput) {
                const cost = parseFloat(propertyCostInput.value);
                const percent = parseFloat(downPaymentInput.value);
                if (!isNaN(cost) && !isNaN(percent)) {
                    const amount = cost * percent / 100;
                    downPaymentAmountDiv.innerHTML = `<i class="fas fa-money-bill-wave me-1"></i> Сумма взноса: <strong>${formatNumber(amount)} ₽</strong> (${percent}%)`;
                } else {
                    downPaymentAmountDiv.innerHTML = '';
                }
            } else {
                downPaymentAmountDiv.innerHTML = '';
            }
        }

        loanCards.forEach(card => {
    card.addEventListener('click', function() {
        loanCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        
        const type = this.dataset.type;
        const name = this.querySelector('.loan-name').innerText;
        
        loanTypeInput.value = type;
        selectedLoanName.innerText = name;
        
        // ========== ДОБАВЛЕНА ОЧИСТКА ПОЛЕЙ ==========
        // Очищаем все поля ввода
        clearAllFields();
        
        if (type === 'consumer') {
            mortgageAutoFields.classList.add('hidden');
            consumerFields.classList.remove('hidden');
        } else {
            mortgageAutoFields.classList.remove('hidden');
            consumerFields.classList.add('hidden');
        }
        
        formContainer.classList.remove('hidden');
        formContainer.scrollIntoView({ behavior: 'smooth' });
    });
});

function clearAllFields() {
    // Очищаем поля ввода
    if (document.getElementById('property_cost')) {
        document.getElementById('property_cost').value = '';
    }
    if (document.getElementById('down_payment_percent')) {
        document.getElementById('down_payment_percent').value = '';
    }
    if (document.getElementById('loan_amount')) {
        document.getElementById('loan_amount').value = '';
    }
    if (document.getElementById('term_months')) {
        document.getElementById('term_months').value = '';
    }
    
    // Очищаем отображение суммы взноса
    if (document.getElementById('downPaymentAmount')) {
        document.getElementById('downPaymentAmount').innerHTML = '';
    }
    
    // Очищаем результаты расчета
    if (document.getElementById('monthly_payment')) {
        document.getElementById('monthly_payment').innerHTML = '0 ₽';
    }
    if (document.getElementById('loan_amount_result')) {
        document.getElementById('loan_amount_result').innerHTML = '0 ₽';
    }
    if (document.getElementById('total_payment')) {
        document.getElementById('total_payment').innerHTML = '0 ₽';
    }
    if (document.getElementById('overpayment')) {
        document.getElementById('overpayment').innerHTML = '0 ₽';
    }
    
    // Скрываем блок результатов
    const resultDiv = document.getElementById('result');
    if (resultDiv) {
        resultDiv.classList.add('hidden');
    }
}

        if (downPaymentInput) {
            downPaymentInput.addEventListener('input', updateDownPaymentAmount);
        }
        if (propertyCostInput) {
            propertyCostInput.addEventListener('input', updateDownPaymentAmount);
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const loanType = loanTypeInput.value;
            if (!loanType) {
                alert('Пожалуйста, выберите тип кредита');
                return;
            }
            
            const termMonths = document.getElementById('term_months').value;
            if (!termMonths || termMonths < 1) {
                alert('Пожалуйста, укажите срок кредита в месяцах');
                return;
            }
            
            let amount;
            if (loanType === 'consumer') {
                amount = document.getElementById('loan_amount').value;
                if (!amount || amount <= 0) {
                    alert('Пожалуйста, укажите сумму кредита');
                    return;
                }
            } else {
                amount = document.getElementById('property_cost').value;
                if (!amount || amount <= 0) {
                    alert('Пожалуйста, укажите стоимость');
                    return;
                }
            }
            
            let loanAmount = amount;
            if (loanType !== 'consumer' && downPaymentInput && downPaymentInput.value) {
                const downPaymentPercent = parseFloat(downPaymentInput.value) || 0;
                loanAmount = amount - (amount * downPaymentPercent / 100);
            }
            
            const formData = new FormData();
            formData.append('loan_type', loanType);
            formData.append('term_months', termMonths);
            formData.append('amount', loanAmount);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            const originalText = calculateBtn.innerHTML;
            calculateBtn.disabled = true;
            calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Расчет...';
            
            try {
                const response = await fetch('/calculate', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    resultDiv.classList.remove('hidden');
                    document.getElementById('monthly_payment').innerHTML = formatNumber(data.monthly_payment) + ' ₽';
                    document.getElementById('loan_amount_result').innerHTML = formatNumber(data.loan_amount) + ' ₽';
                    document.getElementById('total_payment').innerHTML = formatNumber(data.total_payment) + ' ₽';
                    document.getElementById('overpayment').innerHTML = formatNumber(data.overpayment) + ' ₽';
                    resultDiv.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                }
            } catch (error) {
                console.error('Ошибка:', error);
                alert('Ошибка при отправке запроса');
            } finally {
                calculateBtn.disabled = false;
                calculateBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endsection