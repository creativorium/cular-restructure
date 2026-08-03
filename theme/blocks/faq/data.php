<?php
/**
 * Default FAQ content for cular/faq — categories, sub-categories and Q&A,
 * mirroring the live /faqs/ page. Editable here or overridden per block via
 * the ACF fields in fields.php.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * @return array<int,array{title:string,subgroups:array}>
 */
function cular_faq_default_groups() {
	return array(
		array(
			'title'     => 'GENERAL FAQ\'s',
			'subgroups' => array(
				array(
					'title' => '',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'Why should I choose Cular Creative for my digital marketing needs in Bali?',
							'a' => 'We’re a team of in house, passionate creatives who truly care about making a difference.',
						),
						array(
							'q' => 'Where is the Cular Creative location?',
							'a' => 'Pertokoan Nakula Plaza, B1, 2nd Floor, Jl. Nakula, Legian, Kuta, Badung Regency, Bali 80361 (Google Maps)',
						),
						array(
							'q' => 'What digital marketing services do you offer at Cular Creative?',
							'a' => 'We offer: Branding Identity, Social Media Management, Digital Advertising, Graphic Design, Web Development, Content Creation, SEO & Consultancy.',
						),
						array(
							'q' => 'Do you offer any consultations or audits?',
							'a' => 'Yes we do! These are included in our Elevate Services.',
						),
						array(
							'q' => 'Do you have experience working with international clients?',
							'a' => 'Yes, we work with clients in the UAE, Australia, United States and Europe as well other regions of South East Asia.',
						),
						array(
							'q' => 'Can you help me with Indonesian marketing for Indonesian demographics?',
							'a' => 'Yes, we have helped several clients in the past using Bahasa Indonesia targeted at local audiences.',
						),
						array(
							'q' => 'Do you have experience working with small businesses or startups?',
							'a' => 'Yes, we have helped small businesses from concept through to implementation, providing guidance on branding, web dev, and strategy. You don’t need to be an established business to work with us!',
						),
						array(
							'q' => 'Where can I see the price list for your services?',
							'a' => 'Yes, getting a quote is quick and easy! Just fill out our [Cular Client Form] with a few details about your needs, and we’ll get back to you ASAP with a personalized quote.',
						),
						array(
							'q' => 'Can I customize a package based on my budgets?',
							'a' => 'Yes, we can tailor a custom package that fits your needs and your budget.',
						),
						array(
							'q' => 'How do you ensure the security and privacy of my data?',
							'a' => 'We have strict privacy policy contracts and systems in place with all our employees to ensure your data is stored securely and only the team that is involved in your project has access to it.',
						),
						array(
							'q' => 'Do you have any case studies from previous work?',
							'a' => 'Absolutely! Explore our Portfolio Page here and enjoy browsing through our many projects and case studies.',
						),
						array(
							'q' => 'Do you have experience working with E-Commerce project?',
							'a' => 'Yes, we have extensive experience working with Shopify and WooCommerce as e-commerce platforms, as well as advertising for retail. Our team is highly experienced in managing e-commerce projects from ideation to implementation, including social media, advertising, email automation, and more.',
						),
						array(
							'q' => 'What\'s the revision process at Cular Creative ?',
							'a' => 'We include 2 revisions per project for all our services, including branding, web design, copywriting, social media plans and advertising strategies. Any additional revisions will incur extra charges.',
						),
					),
				),
			),
		),
		array(
			'title'     => 'ACTIVATE',
			'subgroups' => array(
				array(
					'title' => 'Website',
					'align' => 'right',
					'items' => array(
						array(
							'q' => 'Do you offer any ongoing website maintenance packages?',
							'a' => 'Yes, we offer ongoing maintenance packages that include website updates, content updates, security monitoring, performance optimization, and technical support. .',
						),
						array(
							'q' => 'How long does it typically take to build a website?',
							'a' => 'It typically takes around 2 months, depending on your website’s complexity, number of pages, integrations, design preferences. Our team will assess your needs and provide a project timeline after we receive your brief.',
						),
						array(
							'q' => 'Do you offer website hosting services?',
							'a' => 'No, we don’t provide hosting ourselves but we can recommend some great hosting options for your website once you decide to work with us!',
						),
						array(
							'q' => 'Do you work on E-Commerce websites?',
							'a' => 'Yes, we have extensive experience working with Shopify and WooCommerce as e-commerce platforms, as well as advertising for retail. Our team is highly experienced in managing e-commerce projects from ideation to implementation, including social media, advertising, email automation, and more.',
						),
						array(
							'q' => 'Can you build websites from scratch? Or do you only do re-designs?',
							'a' => 'Yes, we build websites from scratch tailored to your needs on any CMS of your liking or that is best suitable to your business.',
						),
						array(
							'q' => 'Will my website be mobile-friendly?',
							'a' => 'Yes, all the websites we design are mobile responsive. We ensure a seamless experience on smartphones and tablets, knowing that about 80% of the traffic online comes from mobile nowadays. We prioritize both view and functionality for mobile users.',
						),
					),
				),
				array(
					'title' => 'Social Media Marketing',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'How do you measure the success of your Social Media Marketing efforts?',
							'a' => 'We measure the success of our Social Media Marketing by tracking key metrics such as engagement rates, follower growth, reach, impressions, and website traffic from social media. We also analyze content performance, including click-through rates and top-performing posts, and assess public sentiment to gauge overall impact.',
						),
						array(
							'q' => 'Do you offer online customer service for Social Media Marketing?',
							'a' => 'Yes, we do offer online customer service for Social Media Management. We can assist with setting up and optimizing your social media customer service strategy, ensuring prompt and effective responses to inquiries, managing interactions, and utilizing tools for efficient customer support.',
						),
						array(
							'q' => 'How does your team handle negative comments on Social Media?',
							'a' => 'Our team handles negative comments by responding promptly and empathetically, aiming to resolve issues and move conversations to private channels if needed. We maintain a professional tone, monitor and report feedback for future improvements, and focus on turning negative interactions into positive outcomes to protect and enhance your brand’s reputation.',
						),
					),
				),
				array(
					'title' => 'Digital Advertising',
					'align' => 'right',
					'items' => array(
						array(
							'q' => 'Do you offer any paid advertising services, such as Google Ads or social media advertising?',
							'a' => 'Yes, we offer a range of paid advertising services, including Google and Meta advertising. We can also assist with TikTok and LinkedIn ads as well.',
						),
						array(
							'q' => 'How will you keep me informed about the progress of my digital marketing campaign?',
							'a' => 'You’ll receive detailed monthly reports on the progress of your digital marketing campaign. Additionally, our team will stay in close communication with you via email, WhatsApp, and our project management system as the project evolves.',
						),
						array(
							'q' => 'What is digital advertising, and how can it benefit my business?',
							'a' => 'Digital advertising is a cost-effective way to reach your target audience online. It offers benefits like increased visibility, targeted marketing, and measurable results.',
						),
						array(
							'q' => 'How long does it typically take to see results from a digital marketing campaign?',
							'a' => 'Generally, you can expect to see some results within a few months. However, it’s important to have realistic expectations on input versus output, ie budgets spent, targets, assets, etc. Digital marketing is a long-term investment, and sustained efforts are often required to achieve significant and lasting results.',
						),
						array(
							'q' => 'Can I target specific demographics or locations with digital advertising?',
							'a' => 'Yes, you can target precise demographics, locations, ages, behaviors, genders and interests with digital advertising. This is one of the key benefits of digital marketing, as it allows you to reach the right people.',
						),
						array(
							'q' => 'Do I need to handle managing the campaign myself, or can Cular Creative help?',
							'a' => 'Cular Creative can handle managing your digital marketing campaign. We have a team of certified professionals who will help you with everything from strategy development to campaign execution and optimization.',
						),
						array(
							'q' => 'Does Cular Creative stay compliant with Indonesian regulations for digital advertising?',
							'a' => 'Yes, Cular Creative stays compliant with Indonesian regulations for digital advertising. We are committed to adhering to all relevant laws and guidelines.',
						),
						array(
							'q' => 'How much does digital advertising typically cost?',
							'a' => 'For effective results, we recommend a minimum budget of IDR 10 million per month per platform, as digital ad costs vary based on platform, industry benchmarks, targeted location, ad format, competition, and campaign goals.',
						),
					),
				),
				array(
					'title' => 'Graphic Design',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'Does Cular Creative offer animation/motion graphic creation on the Graphic Design services?',
							'a' => 'Yes, our creative team can make a personalized animation/motion graphics to meet your project needs.',
						),
						array(
							'q' => 'How do you stay updated on the latest design trends and technologies?',
							'a' => 'Our passion for design drives us to stay on top of the latest trends and technologies always. We continuously explore new ideas and inspirations to ensure our work remains fresh, relevant, and innovative.',
						),
					),
				),
				array(
					'title' => 'Content Creation',
					'align' => 'right',
					'items' => array(
						array(
							'q' => 'What kind of content creation do you offer for social media and websites?',
							'a' => 'We provide Videography, Photography, and Company Profiles.',
						),
						array(
							'q' => 'How long does a typical content creation project take from start to finish?',
							'a' => 'Typically, a content creation project takes a minimum of 14 working days, covering everything from concept and moodboard development to production and post-production. The exact timeline can vary based on the volume and complexity of the content.',
						),
						array(
							'q' => 'Can you help with scripting or storyboarding for videos?',
							'a' => 'Yes, we have a talented team of people who can help with creating scripts for your shoot, also a shot list, a moodboard to help your production team to understand your concept, directing on the day of the shoot and post-production management. We can work with our in-house photo-video team or with your preferred team as well.',
						),
						array(
							'q' => 'Do you provide post-production services like editing, color correction, or adding special effects?',
							'a' => 'Yes, we provide all post-production services.',
						),
						array(
							'q' => 'Can you show examples of previous photo and video projects you\'ve worked on?',
							'a' => 'Absolutely You can check out examples of our previous photo and video projects on the Portfolio page of our website. Just head to the ‘Content Creation’ tab to see our work in action!',
						),
					),
				),
				array(
					'title' => 'SEO',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'Can you help me with Search Engine Optimization (SEO) for my website?',
							'a' => 'Yes, we have a range of SEO services, from keyword research and on-page optimization to competitive analysis and link building. We tailor our approach to boost your search rankings and drive traffic depending on your needs.',
						),
						array(
							'q' => 'Can you help me with Social Media for local SEO?',
							'a' => 'Yes, we can help with local SEO! We use social media to enhance your local presence by sharing relevant content, using geotags and local hashtags, and promoting local offers. Social media captions contribute highly to your digital footprint so we ensure your captions are SEO optimised as well.',
						),
					),
				),
			),
		),
		array(
			'title'     => 'ELEVATE',
			'subgroups' => array(
				array(
					'title' => 'MARKETING AUDIT',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'What is a Marketing Audit?',
							'a' => 'A Marketing Audit is a comprehensive review and evaluation of your marketing activities, strategies, and performance. The goal is to assess how effectively these elements contribute to achieving your business objectives and to identify areas for improvement.',
						),
						array(
							'q' => 'How long does a marketing audit typically take?',
							'a' => 'A marketing audit takes about a week, though this can vary based on the depth and scope of the audit.',
						),
						array(
							'q' => 'Why should I conduct a marketing audit for my business?',
							'a' => 'A marketing audit helps identify what’s working, what’s not, and where to improve, ensuring your strategies align with your business goals for better results.',
						),
						array(
							'q' => 'What channels do you analyse when creating a Marketing Audit?',
							'a' => 'Our marketing audit covers a range of channels, including Social Media, Meta and Google Ads, SEO, and Web Performance.',
						),
					),
				),
				array(
					'title' => 'BLUEPRINT STRATEGY',
					'align' => 'right',
					'items' => array(
						array(
							'q' => 'What is a Digital Marketing Blueprint Strategy?',
							'a' => 'A Digital Marketing Strategy Blueprint is a comprehensive plan that outlines how to effectively achieve your marketing goals. It involves creating strategies for launching new campaigns or refining existing ones for maximum success.',
						),
						array(
							'q' => 'Do you provide a Competitor Analysis as part of the Blueprint Strategy?',
							'a' => 'Yes, our Strategy Blueprint includes a thorough Competitor Analysis. We consider all key factors during our research to ensure our strategies are well-informed and effective.',
						),
						array(
							'q' => 'How does a Blueprint Strategy help my business grow online?',
							'a' => 'A Strategy Blueprint helps your business grow online by providing a clear, actionable plan tailored to your goals. It outlines strategic steps to enhance your online visibility, increase engagement with your audience, and drive higher conversions, making sure your efforts are focused and effective.',
						),
					),
				),
				array(
					'title' => 'CONSULTANCY',
					'align' => 'left',
					'items' => array(
						array(
							'q' => 'What is Digital Marketing Consultancy?',
							'a' => 'Our marketing Consultancy services provide expert guidance to elevate your marketing efforts. Our consultancy services are designed to provide continuous support, strategic insights, and actionable steps to help you achieve your business goals by supporting your internal team and helping you find a flow that works for you.',
						),
						array(
							'q' => 'Can I hire you for a one-time consultation, or do you offer ongoing support?',
							'a' => 'Both! We offer one-time consultations, as well as ongoing support. We provide consultations by the hour, or for team training, strategy sessions and implementation through our list of digital makeritng services. So whether you just need the support, a plan or someone to execute the plan – we’ve got you.',
						),
						array(
							'q' => 'Do you offer virtual or in-person consultations?',
							'a' => 'Both! Depending on your location and availability, we can arrange a consultation format that works best for you.',
						),
					),
				),
			),
		),
	);
}
