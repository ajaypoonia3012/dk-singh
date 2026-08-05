@if($theme?->show_bmi)

<!-- BMI -->

<section class="py-24 bg-[#f6f3eb]">

    {{-- Paste the entire BMI section extracted from index.blade.php here --}}

</section>

<script>

function calculateBMI() {

    const height = parseFloat(document.getElementById('height').value) / 100;
    const weight = parseFloat(document.getElementById('weight').value);

    if (!height || !weight) {
        return;
    }

    const bmi = (weight / (height * height)).toFixed(1);

    document.getElementById('bmi-result').innerText = bmi;

    let status = '';
    let goal = '';

    if (bmi < 18.5) {

        status = 'Underweight';
        goal = 'Muscle gain and nutrition optimization program recommended.';

    } else if (bmi < 25) {

        status = 'Healthy';
        goal = 'Maintain your fitness with performance training programs.';

    } else if (bmi < 30) {

        status = 'Overweight';
        goal = 'Fat loss transformation program recommended.';

    } else {

        status = 'Obese';
        goal = 'Structured weight loss coaching strongly recommended.';

    }

    document.getElementById('bmi-status').innerText = status;
    document.getElementById('bmi-goal').innerText = goal;
}

</script>

@endif