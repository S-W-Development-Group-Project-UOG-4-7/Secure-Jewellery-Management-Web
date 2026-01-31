// backend/controllers/designController.js (Example using OpenAI)
const { OpenAI } = require('openai');
require('dotenv').config();

const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY,
});

exports.generateImage = async (req, res) => {
  try {
    // 1. Get user selections from the request body
    const { prompt, type, material, gemstone } = req.body;

    // 2. Build a professional jewelry prompt
    const aiPrompt = `Photorealistic ${type} design, ${material}, featuring a ${gemstone} center stone. ${prompt}. Detailed jewelry rendering, studio lighting, clean background.`;

    // 3. Call the AI Image API
    const response = await openai.images.generate({
      model: "dall-e-3",
      prompt: aiPrompt,
      n: 1,
      size: "1024x1024",
      quality: "hd"
    });

    // 4. Send the image URL back to the frontend
    const imageUrl = response.data[0].url;
    res.status(200).json({
      success: true,
      data: imageUrl,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({
      success: false,
      error: 'Image generation failed',
    });
  }
};