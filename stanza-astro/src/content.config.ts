import { defineCollection, z } from 'astro:content';

const collectionsCollection = defineCollection({
  type: 'data',
  schema: z.object({
    title: z.string(),
    author: z.string(),
    order: z.number().optional(),
    cover: z.string().optional(),
  }),
});

const poemsCollection = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    collection: z.string(),
    lang: z.enum(['en', 'el']),
    translationKey: z.string().optional(),
    date: z.coerce.date().optional(),
    draft: z.boolean().optional().default(false),
  }),
});

export const collections = {
  collections: collectionsCollection,
  poems: poemsCollection,
};
