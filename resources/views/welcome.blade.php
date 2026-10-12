<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NCAA EPPMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        section {
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="bg-gray-50">
    <section class="relative overflow-hidden flex flex-col justify-start pt-20 pb-32 px-4 md:px-6"
        id="hero-carousel-wrapper">
        <div id="bg1" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100 z-0"
            style="background-image: url('{{ asset('assets/images/landing/hero-bg.png') }}');"></div>
        <div id="bg2" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0 z-0">
        </div>

        <div id="hero-section" class="relative z-10 w-full">
            <header
                class="mx-auto z-20 bg-[#7272724D] bg-opacity-50 w-[90%] py-3 px-4 md:px-6 flex items-center justify-between rounded-lg">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('/assets/images/landing/ncaalogo 2.png') }}" alt="NCAA Logo"
                        class="w-10 h-10 rounded-full" />
                    <span class="text-white text-lg font-semibold">NCAA</span>
                </div>
                <div class="flex gap-2">
                    @if (Auth::check())
                        <a href="{{ route('home') }}">
                            <button
                                class="bg-[#E4E6FF] text-[#01054C] px-4 md:px-6 py-2 rounded-lg hover:bg-[#01054C] hover:text-white text-sm md:text-base">
                                NCAA
                            </button>
                        </a>
                    @else
                        <a href="{{ route('login') }}">
                            <button
                                class="bg-[#01054C] text-white px-8 py-2 rounded-lg hover:bg-[#E4E6FF] hover:text-[#01054C]">
                                Login
                            </button>
                        </a>
                    @endif
                </div>
            </header>

            <div class="flex flex-col md:flex-row items-center mt-16 w-[80%] mx-auto gap-8">
                <div>
                    <div
                        class="w-full lg:w-[50%] text-white text-center md:text-left p-4 sm:px-8 bg-[#97979780] bg-opacity-50">
                        <div
                            class="w-full sm:w-[80%] mx-auto md:mx-0 hidden md:flex items-center bg-[#E1FFDA] text-[#065F46] px-4 py-2 rounded-full justify-between mb-4 shadow-md">
                            <img src="{{ asset('/assets/images/landing/hand.png') }}" alt="Icon" class="w-6 h-6" />
                            <span class="font-semibold text-sm md:text-base text-center">Optimize Projects. Drive
                                Success.</span>
                            <img src="{{ asset('/assets/images/landing/dollar.png') }}" alt="Icon"
                                class="w-6 h-6" />
                        </div>

                        <h1 class="text-xl md:text-3xl lg:text-4xl mt-20 md:mt-0">
                            Welcome to the NCAA's Electronic Project Portfolio Management System (EPPMS).
                        </h1>

                        <p class="mt-4 text-[14px] sm:text-[16px] max-w-lg lg:max-w-xl leading-relaxed">
                            Streamline project lifecycles, enhance collaboration, and ensure on-time, on-budget delivery
                            with our robust and intuitive platform. EPPMS empowers your teams to manage complex projects
                            effectively, fostering transparency and accountability across all initiatives.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-5 justify-center md:justify-start">
                        @if (Auth::check())
                            <a href="{{ route('home') }}">
                                <button
                                    class="bg-[#E4E6FF] text-[#01054C] px-5 md:px-6 py-2 rounded-lg hover:bg-[#01054C] hover:text-white transition-all">
                                    Explore live projects
                                </button>
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                <button
                                    class="bg-[#01054C] text-white px-6 md:px-8 py-2 rounded-lg hover:bg-[#E4E6FF] hover:text-[#01054C] transition-all">
                                    Login
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-4 md:px-6 w-full max-w-full box-border">
        <div class="relative overflow-hidden container mx-auto items-center justify-between">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/images/landing/g12.png') }}')] bg-no-repeat bg-center bg-contain opacity-10 z-0"
                aria-hidden="true"></div>
            <div class="z-10 grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-8 rounded-xl bg-white items-stretch">
                <div class="flex flex-col space-y-4 h-full">
                    <p class="text-3xl">About NCAA</p>
                    <p class="text-sm">
                        Nigeria Civil Aviation Authority is the regulatory body for
                        aviation in Nigeria. It became autonomous with the passing into
                        law of the Civil Aviation Act 2006 by the National Assembly and
                        assent of the President of the Federal Republic of Nigeria.
                    </p>
                </div>

                <div class="z-10 flex flex-col items-center w-full h-full">
                    <div class="space-y-4 bg-[#D5D8FF] p-4 rounded-lg flex flex-col h-full w-full">
                        <div class="flex items-center gap-2 bg-[#000671] p-2 rounded-lg text-white">
                            <img src="{{ asset('/assets/images/landing/vision.png') }}" alt="" />
                            <p class="text-lg">Vision</p>
                        </div>
                        <p class="text-sm text-[#575757]">
                            To be one of the leading civil aviation authorities in the
                            world.
                        </p>
                    </div>
                </div>

                <div class="z-10 space-y-4 bg-[#D5D8FF] p-4 rounded-lg flex flex-col h-full">
                    <div class="flex items-center gap-2 bg-[#000671] p-2 rounded-lg text-white">
                        <img src="{{ asset('/assets/images/landing/mission.png') }}" alt="" />
                        <p class="text-lg">Mission</p>
                    </div>
                    <p class="text-sm text-[#575757]">
                        To provide aviation safety and economic regulation in the most
                        efficient, effective, quality and technology driven manner to the
                        satisfaction and benefit of all stakeholders, consistent with the
                        highest international standards and the sustainable development of
                        the industry and national economy.
                    </p>
                </div>
            </div>
        </div>

        <div class="container mx-auto flex items-center justify-center">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 p-6 rounded-lg">
                <div>
                    <img src="{{ asset('/assets/images/landing/minister.png') }}" alt="Minister"
                        class="max-w-full h-auto" />
                </div>

                <div>
                    <img src="{{ asset('/assets/images/landing/25 3.png') }}" alt="" />
                </div>
                <div>
                    <img src="{{ asset('/assets/images/landing/man 1.png') }}" alt="Minister"
                        class="max-w-full h-auto" />
                </div>
            </div>
        </div>
    </section>

    <section class="px-2 md:px-4 text-center w-full max-w-full mb-2 overflow-hidden">
        <div class="container mx-auto bg-white rounded-lg px-4 py-16 relative">
            <h2 class="text-xl md:text-3xl">
                Your Centralized Project Management Solution.
            </h2>
            <p class="mt-4 text-base md:text-lg mb-10">
                Manage every aspect of your projects with our intuitive platform, from
                planning and tracking to reporting and analysis.
            </p>

            <div class="relative w-full max-w-6xl mx-auto  h-[300px] sm:h-[400px] md:h-[500px] lg:h-[550px]  left-[9%]">
                <img src="{{ asset('/assets/images/landing/Screenshot (15) 1.png') }}"
                    class="absolute top-0 left-[5%] w-[40%] sm:w-[35%] md:w-[50%] shadow-lg rounded-lg z-10"
                    alt="" />
                <img src="{{ asset('/assets/images/landing/Screenshot (15) 4.png') }}"
                    class="absolute top-[15%] left-[15%] w-[40%] sm:w-[35%] md:w-[50%] shadow-lg rounded-lg z-20"
                    alt="" />
                <img src="{{ asset('/assets/images/landing/Screenshot (15) 5.png') }}"
                    class="absolute top-[30%] left-[25%] w-[40%] sm:w-[35%] md:w-[50%] shadow-lg rounded-lg z-30"
                    alt="" />
            </div>
        </div>
        <div class="">
            @guest
                <a href="{{ route('login') }}">
                    <button
                        class="mt-6 bg-[#01054C] px-10 py-3 rounded-lg text-white hover:bg-blue-700 text-sm sm:text-base">
                        Login
                    </button>
                </a>
            @endguest
        </div>
    </section>

    <section class=" ">
        <div class="container mx-auto bg-white mx-auto rounded-lg flex flex-col gap-7 items-center w-full">
            <div class="flex-col flex items-center p-4">
                <h2 class="text-xl md:text-3xl">Features for Project management</h2>
                <p class="mt-4 text-center md:text-lg">
                    The EPPMS’ features are crafted to enhance collaboration, streamline
                    on-budget delivery and effective management fostering transparency
                    and accountability across all initiatives.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 p-6 rounded-lg">
                <div
                    class="relative group space-y-5 text-center md:text-left rounded transition-all duration-300 hover:shadow-lg hover:p-6 p-4 hover:bg-[#01054C] bg-[#BABEFF]">
                    <div class="absolute top-0 right-0">
                        <img src="{{ asset('/assets/images/landing/World_map_(blue_dots) 2.png') }}"
                            alt="" />
                    </div>
                    <img src="{{ asset('/assets/images/landing/1 (2).png') }}" alt="Real-Time Project Tracking"
                        class="mx-auto md:mx-0 w-12 h-12" />
                    <h3 class="text-xl group-hover:text-white transition-colors duration-300">
                        Real-Time Project Tracking
                    </h3>
                    <p class="text-gray-700 group-hover:text-white transition-colors duration-300">
                        Monitor project progress, milestones, and task completion in
                        real-time. Gain immediate insights into project status and
                        potential bottlenecks.
                    </p>
                </div>

                <div
                    class="relative group space-y-5 text-center md:text-left rounded transition-all duration-300 hover:shadow-lg hover:p-6 p-4 hover:bg-[#01054C] bg-[#BABEFF]">
                    <div class="absolute top-0 right-0">
                        <img src="{{ asset('/assets/images/landing/World_map_(blue_dots) 2.png') }}"
                            alt="" />
                    </div>
                    <img src="{{ asset('/assets/images/landing/2 (2).png') }}" alt="Efficient Resource Management"
                        class="mx-auto md:mx-0 w-12 h-12" />
                    <h3 class="text-xl group-hover:text-white transition-colors duration-300">
                        Efficient Resource Management
                    </h3>
                    <p class="text-gray-700 group-hover:text-white transition-colors duration-300">
                        Allocate resources effectively, track utilization, and optimize
                        resource distribution across projects. Ensure projects are
                        adequately staffed and resourced.
                    </p>
                </div>

                <div
                    class="relative group space-y-5 text-center md:text-left rounded transition-all duration-300 hover:shadow-lg hover:p-6 p-4 hover:bg-[#01054C] bg-[#BABEFF]">
                    <div class="absolute top-0 right-0">
                        <img src="{{ asset('/assets/images/landing/World_map_(blue_dots) 2.png') }}"
                            alt="" />
                    </div>
                    <img src="{{ asset('/assets/images/landing/1.png') }}" alt="Collaborative Task Management"
                        class="mx-auto md:mx-0 w-12 h-12" />
                    <h3 class="text-xl group-hover:text-white transition-colors duration-300">
                        Collaborative Task Management
                    </h3>
                    <p class="text-gray-700 group-hover:text-white transition-colors duration-300">
                        Assign tasks, share updates, and facilitate communication within a
                        centralized platform. Enhance team coordination and streamline
                        workflows.
                    </p>
                </div>
                <div
                    class="relative group space-y-5 text-center md:text-left rounded transition-all duration-300 hover:shadow-lg hover:p-6 hover:bg-[#01054C] bg-[#BABEFF] p-4">
                    <div class="absolute top-0 right-0">
                        <img src="{{ asset('/assets/images/landing/World_map_(blue_dots) 2.png') }}"
                            alt="" />
                    </div>
                    <img src="{{ asset('/assets/images/landing/2.png') }}" alt="Comprehensive Reporting"
                        class="mx-auto md:mx-0 w-12 h-12" />
                    <h3 class="text-xl group-hover:text-white transition-colors duration-300">
                        Comprehensive Reporting and Analytics
                    </h3>
                    <p class="text-gray-700 group-hover:text-white transition-colors duration-300">
                        Generate detailed reports, analyze project performance, and make
                        informed decisions. Leverage data analytics to drive continuous
                        improvement and project success.
                    </p>
                </div>
            </div>
            <div>
                @auth
                    <a href="{{ route('home') }}">
                        <button
                            class="bg-[#01054C] px-6 py-3 rounded-lg text-white hover:bg-blue-700 transition-colors duration-300">
                            Explore Application
                        </button>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <section>
        <div
            class="container py-28 px-4 md:px-6 mx-auto my-5 rounded-lg text-center w-full bg-[#DBDDFF] relative overflow-hidden">
            <div class="hidden lg:block absolute top-0 right-0 z-10">
                <img src="{{ asset('/assets/images/landing/aircraft.png') }}" alt="Aircraft" class="max-w-full" />
            </div>

            <div class="hidden lg:block absolute bottom-0 left-0 z-10">
                <img src="{{ asset('/assets/images/landing/Group 331.png') }}" alt="Group" class="max-w-full" />
            </div>

            <div class="relative z-20">
                <h2 class="text-lg md:text-2xl w-full md:w-[60%] lg:w-[40%] mx-auto">
                    Maximize project success. Our ePPMS provides robust portfolio
                    management tools for informed decision-making.
                </h2>
                @guest
                    <a href="{{ route('login') }}">
                        <button
                            class="mt-6 bg-[#01054C] px-10 py-3 rounded-lg text-white hover:bg-blue-700 text-sm sm:text-base">
                            Login
                        </button>
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <section
        class="py-10 px-4 md:px-10 mx-auto my-5 rounded-lg w-full max-w-[calc(100%-10px)] bg-[#01054C] text-white">
        <div
            class="flex flex-col md:flex-row items-center justify-between mb-10 md:mb-20 text-center md:text-left gap-6">
            <h2 class="text-2xl md:text-4xl w-full md:w-[50%] leading-tight">
                Electronic Project Portfolio Management System
            </h2>
            <p class="text-sm w-full md:w-[39%]">
                Streamline project lifecycles, enhance collaboration, and ensure
                on-time, on-budget delivery with our robust and intuitive platform.
                ePPMS empowers your teams to manage complex projects effectively,
                fostering transparency and accountability across all initiatives.
            </p>
        </div>

        <hr class="mb-10 border-gray-400" />

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('/assets/images/landing/ncaalogo 2.png') }}" alt="NCAA Logo"
                    class="w-10 h-10 rounded-full" />
                <span class="text-white text-lg font-semibold">NCAA</span>
            </div>
            <p class="text-sm md:text-base">© All rights reserved • 2025</p>
        </div>
    </section>
    <script>
        const bg1 = document.getElementById('bg1');
        const bg2 = document.getElementById('bg2');

        const images = [
            "{{ asset('assets/images/landing/bg-2.png') }}",
            "{{ asset('assets/images/landing/bg-3.png') }}",
            "{{ asset('assets/images/landing/bg-4.png') }}",
        ];

        let index = 0;
        let showingFirst = true;

        setInterval(() => {
            index = (index + 1) % images.length;
            if (showingFirst) {
                bg2.style.backgroundImage = `url('${images[index]}')`;
                bg2.classList.remove('opacity-0');
                bg2.classList.add('opacity-100');
                bg1.classList.remove('opacity-100');
                bg1.classList.add('opacity-0');
            } else {
                bg1.style.backgroundImage = `url('${images[index]}')`;
                bg1.classList.remove('opacity-0');
                bg1.classList.add('opacity-100');
                bg2.classList.remove('opacity-100');
                bg2.classList.add('opacity-0');
            }
            showingFirst = !showingFirst;
        }, 5000);
    </script>


</body>

</html>
